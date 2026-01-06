$sourceDir = "D:\emsi\programmation mobile\costumeapp\backend\image"
$destDir = "D:\emsi\programmation mobile\costumeapp\backend\storage\app\public\costumes"

if (!(Test-Path $destDir)) {
    New-Item -ItemType Directory -Path $destDir -Force | Out-Null
}

$counter = 1
Get-ChildItem -LiteralPath $sourceDir -File | ForEach-Object {
    $newName = "costume-$counter.jpg"
    $destPath = Join-Path $destDir $newName
    Copy-Item -LiteralPath $_.FullName -Destination $destPath -Force
    Write-Host "Copied: $newName"
    $counter++
}

Write-Host "`nTotal: $($counter-1) images copied successfully"
