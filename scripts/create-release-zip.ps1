param(
    [string]$Version = ""
)

$ErrorActionPreference = 'Stop'

$themeRoot = Split-Path -Parent $PSScriptRoot
Set-Location $themeRoot

if ([string]::IsNullOrWhiteSpace($Version)) {
    $packageJsonPath = Join-Path $themeRoot 'package.json'
    $packageJson = Get-Content $packageJsonPath -Raw | ConvertFrom-Json
    $Version = $packageJson.version
}

npm run build

$releaseDir = Join-Path $themeRoot 'release'
New-Item -ItemType Directory -Path $releaseDir -Force | Out-Null

$tempRoot = Join-Path $themeRoot '.release-tmp'
$stagingDir = Join-Path $tempRoot 'wp_restatify-base-theme'

if (Test-Path $tempRoot) {
    Remove-Item $tempRoot -Recurse -Force
}

New-Item -ItemType Directory -Path $stagingDir -Force | Out-Null

$includeItems = @(
    'assets',
    'blocks',
    'build',
    'inc',
    'parts',
    'patterns',
    'styles',
    'templates',
    'functions.php',
    'style.css',
    'theme.json',
    'screenshot.png',
    'readme.txt',
    'README.de.md'
)

foreach ($item in $includeItems) {
    $sourcePath = Join-Path $themeRoot $item
    if (Test-Path $sourcePath) {
        Copy-Item $sourcePath -Destination $stagingDir -Recurse -Force
    }
}

$zipPath = Join-Path $releaseDir ("wp_restatify-base-theme-$Version.zip")
if (Test-Path $zipPath) {
    Remove-Item $zipPath -Force
}

Compress-Archive -Path (Join-Path $tempRoot 'wp_restatify-base-theme') -DestinationPath $zipPath -CompressionLevel Optimal
Remove-Item $tempRoot -Recurse -Force

Write-Output "Created release package: $zipPath"
