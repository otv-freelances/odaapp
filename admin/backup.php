<?php
/**
 * admin/backup.php
 *
 * Creates a timestamped ZIP archive of data/ + uploads/ and
 * offers it as a browser download. Can also be called by a cron job
 * in CLI mode to write the archive to a local folder for rclone pickup.
 *
 * Cron example (daily at 2am, writes to /backups/):
 *   0 2 * * * php /path/to/htdocs/admin/backup.php --cron >> /var/log/site-backup.log 2>&1
 */

// ── CLI mode (cron) ───────────────────────────────────────────────────────
$isCron = (php_sapi_name() === 'cli') || in_array('--cron', $argv ?? []);

if (!$isCron) {
    require_once __DIR__ . '/auth.php';
    require_auth();
    require_setup();
} else {
    require_once dirname(__DIR__) . '/config.php';
}

if (!class_exists('ZipArchive')) {
    $msg = "Error: PHP ZipArchive extension is not available.\n";
    if ($isCron) { echo $msg; exit(1); }
    http_response_code(500);
    exit($msg);
}

// ── Build archive ─────────────────────────────────────────────────────────
$timestamp = date('Y-m-d_H-i-s');
$filename  = 'backup-' . $timestamp . '.zip';
$tmpPath   = sys_get_temp_dir() . '/' . $filename;

$zip  = new ZipArchive();
$res  = $zip->open($tmpPath, ZipArchive::CREATE | ZipArchive::OVERWRITE);
if ($res !== true) {
    $msg = "Error: Could not create ZIP file (code {$res}).\n";
    if ($isCron) { echo $msg; exit(1); }
    http_response_code(500);
    exit($msg);
}

/**
 * Recursively add a directory to the ZIP archive.
 */
function add_dir_to_zip(ZipArchive $zip, string $diskPath, string $zipPrefix): void {
    if (!is_dir($diskPath)) return;
    $items = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator($diskPath, FilesystemIterator::SKIP_DOTS),
        RecursiveIteratorIterator::SELF_FIRST
    );
    foreach ($items as $item) {
        $real   = $item->getRealPath();
        $rel    = $zipPrefix . '/' . ltrim(str_replace($diskPath, '', $real), DIRECTORY_SEPARATOR);
        $rel    = str_replace('\\', '/', $rel); // normalise on Windows
        if ($item->isDir()) {
            $zip->addEmptyDir($rel);
        } else {
            $zip->addFile($real, $rel);
        }
    }
}

add_dir_to_zip($zip, DATA_DIR,    'data');
add_dir_to_zip($zip, UPLOADS_DIR, 'uploads');

$zip->close();

// ── Cron path: save to configured BACKUP_DIR for rclone ────────────────────
if ($isCron) {
    $backupDir = (defined('BACKUP_DIR') && BACKUP_DIR !== '')
        ? BACKUP_DIR
        : dirname(APP_ROOT) . '/site-backups';
    if (!is_dir($backupDir)) mkdir($backupDir, 0750, true);
    $dest = $backupDir . '/' . $filename;
    if (rename($tmpPath, $dest)) {
        echo "[" . date('c') . "] Backup saved: {$dest}\n";

        // ── Keep only the last 14 backups (2 weeks) ─────────────────────
        $old = glob($backupDir . '/backup-*.zip') ?: [];
        rsort($old); // newest first
        foreach (array_slice($old, 14) as $stale) {
            unlink($stale);
            echo "[" . date('c') . "] Removed old backup: {$stale}\n";
        }
    } else {
        echo "[" . date('c') . "] Error: Could not move backup to {$dest}\n";
        exit(1);
    }
    exit(0);
}

// ── Browser: stream ZIP as download ──────────────────────────────────────
if (!file_exists($tmpPath)) {
    http_response_code(500);
    exit('Backup file not found.');
}

header('Content-Type: application/zip');
header('Content-Disposition: attachment; filename="' . $filename . '"');
header('Content-Length: ' . filesize($tmpPath));
header('Cache-Control: no-cache, no-store, must-revalidate');
header('Pragma: no-cache');
header('Expires: 0');

readfile($tmpPath);
@unlink($tmpPath); // clean up temp file
exit;
