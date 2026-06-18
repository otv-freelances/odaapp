$html = [System.IO.File]::ReadAllText("C:\xampp\htdocs\data\partners_page.html")

# Skip logos - filenames for ODA's own brand images
$skipFiles = @('ODA_edited_edited','i%20can%20program','ODA.png','9ecce92','658e2a5')

# Get all partner image matches with position  
$imgMatches = [regex]::Matches($html, 'src="(https://static\.wixstatic\.com/media/4f3a64[^"]+)"')

$results = @()
$seen = [System.Collections.Generic.HashSet[string]]::new()

foreach ($imgM in $imgMatches) {
    $imgUrl = $imgM.Groups[1].Value
    # Skip ODA brand logos
    $skip = $false
    foreach ($s in $skipFiles) { if ($imgUrl -match $s) { $skip = $true; break } }
    if ($skip) { continue }
    
    # Skip if already processed this image hash
    $hashMatch = [regex]::Match($imgUrl, '4f3a64_[a-f0-9]+')
    if ($hashMatch.Success) {
        if ($seen.Contains($hashMatch.Value)) { continue }
        [void]$seen.Add($hashMatch.Value)
    }
    
    # Find the next h2 after this image  
    $pos = $imgM.Index + $imgM.Length
    $remaining = $html.Substring($pos, [Math]::Min(3000, $html.Length - $pos))
    
    $h2M = [regex]::Match($remaining, '<h2[^>]*>([\s\S]+?)</h2>')
    if (-not $h2M.Success) { continue }
    
    $name = [regex]::Replace($h2M.Groups[1].Value, '<[^>]+>', '') -replace '&quot;','"' -replace '&#39;',"'" -replace '&amp;','&' -replace '&nbsp;',' ' -replace '\s+',' '
    $name = $name.Trim()
    if (-not $name -or $name -match "^(ON-DECK ALLIANCE|Thank you!|Our Sponsors)" ) { continue }
    
    # Find the next p.font_7 after the h2
    $afterH2 = $remaining.Substring($h2M.Index + $h2M.Length, [Math]::Min(2000, $remaining.Length - $h2M.Index - $h2M.Length))
    $pM = [regex]::Match($afterH2, 'font_7[^>]*>([\s\S]+?)</p>')
    $desc = if ($pM.Success) {
        $raw = [regex]::Replace($pM.Groups[1].Value, '<[^>]+>', '') -replace '&quot;','"' -replace '&#39;',"'" -replace '&amp;','&' -replace '&nbsp;',' ' -replace '\s+',' '
        $raw.Trim()
    } else { "" }
    
    $results += [PSCustomObject]@{ Name=$name; Img=$imgUrl; Desc=$desc }
    Write-Output "=== $name ==="
    Write-Output "IMG: $imgUrl"
    Write-Output "DESC: $desc"
    Write-Output ""
}

Write-Output "---"
Write-Output "Total partners: $($results.Count)"
$results | ConvertTo-Json | Out-File "C:\xampp\htdocs\data\partners.json" -Encoding UTF8
Write-Output "Saved to partners.json"
