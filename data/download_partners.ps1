$outDir = "C:\xampp\htdocs\images\partners"
if (-not (Test-Path $outDir)) { New-Item -ItemType Directory -Path $outDir | Out-Null }

$partners = @(
  @{ file="frederick-douglass.png";   url="https://static.wixstatic.com/media/4f3a64_c60ceaaf59e44988b834147d754b497e~mv2.png/v1/fill/w_680,h_606,al_c,q_90,usm_0.66_1.00_0.01,enc_avif,quality_auto/thedoug_edited.png" },
  @{ file="toledo-youth-services.jpg"; url="https://static.wixstatic.com/media/4f3a64_0d113935bcd34296b59d06af77ac1d10~mv2.jpg/v1/fill/w_680,h_606,al_c,q_85,usm_0.66_1.00_0.01,enc_avif,quality_auto/tol_logo.jpg" },
  @{ file="dubs-foundation.png";       url="https://static.wixstatic.com/media/4f3a64_7124ad84c7294babac02bca38978ad6f~mv2.png/v1/fill/w_680,h_606,al_c,q_90,usm_0.66_1.00_0.01,enc_avif,quality_auto/20230911_085322_0000_edited.png" },
  @{ file="advocate-skateworks.webp";  url="https://static.wixstatic.com/media/4f3a64_7e85c2a5e5164c7c9fcbceb4fdaa6b01~mv2.webp/v1/fill/w_538,h_479,al_c,lg_1,q_80,enc_avif,quality_auto/advsk8works.webp" },
  @{ file="pizza-cat-toledo.png";      url="https://static.wixstatic.com/media/4f3a64_de94457db1014b6cae3e3b4bf187fb97~mv2.png/v1/fill/w_680,h_606,al_c,q_90,usm_0.66_1.00_0.01,enc_avif,quality_auto/pizzacatlogo_edited.png" },
  @{ file="nuclear-skate-cult.jpg";    url="https://static.wixstatic.com/media/4f3a64_8487fb5e6a294e2c80aa7b02d8767598~mv2.jpg/v1/fill/w_680,h_606,al_c,q_85,usm_0.66_1.00_0.01,enc_avif,quality_auto/CamScanner%2008-08-2023%2009_43_1.jpg" },
  @{ file="steez-llc.webp";            url="https://static.wixstatic.com/media/4f3a64_285421162c71480ca776297553a3534a~mv2.webp/v1/fill/w_680,h_606,al_c,q_85,usm_0.66_1.00_0.01,enc_avif,quality_auto/steezlogo.webp" },
  @{ file="lavish-hardware.png";       url="https://static.wixstatic.com/media/4f3a64_9fe1069d191c44fd8ad7e69a66816d70~mv2.png/v1/fill/w_680,h_606,al_c,q_90,usm_0.66_1.00_0.01,enc_avif,quality_auto/Screen%20Shot%202025-01-03%20at%208_49_25%20PM.png" },
  @{ file="modern-skate-shop.png";     url="https://static.wixstatic.com/media/4f3a64_a80cfac7d10b457c87cd290c09e0e637~mv2.png/v1/fill/w_680,h_606,al_c,q_90,usm_0.66_1.00_0.01,enc_avif,quality_auto/IMG_20230808_093605_edited.png" },
  @{ file="just-skateboarding.jpg";    url="https://static.wixstatic.com/media/4f3a64_34ed9f987db74f1e8539c8c458fa4fe9~mv2.jpg/v1/fill/w_680,h_606,al_c,q_85,usm_0.66_1.00_0.01,enc_avif,quality_auto/IMG_20230808_093627.jpg" },
  @{ file="rynos-skate-shop.jpg";      url="https://static.wixstatic.com/media/4f3a64_0ef15692c1914d0b865c739eb1bba391~mv2.jpg/v1/fill/w_680,h_606,al_c,q_85,usm_0.66_1.00_0.01,enc_avif,quality_auto/CamScanner%2008-08-2023%2009_edited.jpg" },
  @{ file="lil-jawns.png";             url="https://static.wixstatic.com/media/4f3a64_d1f5f83acc734285af7ad337ebb97f67~mv2.png/v1/fill/w_680,h_606,al_c,lg_1,q_90,enc_avif,quality_auto/liljawns.png" },
  @{ file="vans.webp";                 url="https://static.wixstatic.com/media/4f3a64_db0a5bf810294cfab2e341eb6f8d79e4~mv2.webp/v1/fill/w_680,h_606,al_c,q_85,usm_0.66_1.00_0.01,enc_avif,quality_auto/vans%20logo.webp" },
  @{ file="printed-on-a-lark.png";     url="https://static.wixstatic.com/media/4f3a64_c20046867d8b4a988f5508b6dd5dda43~mv2.png/v1/fill/w_680,h_606,al_c,q_90,usm_0.66_1.00_0.01,enc_avif,quality_auto/Screen%20Shot%202025-01-05%20at%208_edited.png" },
  @{ file="smollie-skateboard.webp";   url="https://static.wixstatic.com/media/4f3a64_191de95122764acfb59b9ed40ef54d2c~mv2.webp/v1/fill/w_448,h_399,al_c,lg_1,q_80,enc_avif,quality_auto/smollielogo.webp" },
  @{ file="keen-ramps.png";            url="https://static.wixstatic.com/media/4f3a64_40b11542c99643b2ba3bb4547e9c7f2e~mv2.png/v1/fill/w_315,h_281,al_c,lg_1,q_85,enc_avif,quality_auto/keenrampslogo.png" },
  @{ file="indo-board.png";            url="https://static.wixstatic.com/media/4f3a64_952be9e62a0045d880cbb49dd8006eae~mv2.png/v1/fill/w_680,h_606,al_c,q_90,enc_avif,quality_auto/indoboardlogo_edited_edited.png" }
)

$wc = New-Object System.Net.WebClient
$wc.Headers.Add("User-Agent", "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36")
$wc.Headers.Add("Referer", "https://www.on-deckalliance.org/")

foreach ($p in $partners) {
  $dest = Join-Path $outDir $p.file
  if (Test-Path $dest) {
    Write-Output "SKIP (exists): $($p.file)"
    continue
  }
  try {
    $wc.DownloadFile($p.url, $dest)
    $size = (Get-Item $dest).Length
    Write-Output "OK [$size bytes]: $($p.file)"
  } catch {
    Write-Output "FAIL: $($p.file) => $_"
  }
}
$wc.Dispose()
Write-Output "Done."
