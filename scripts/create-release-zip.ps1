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

Add-Type -AssemblyName System.IO.Compression
Add-Type -AssemblyName System.IO.Compression.FileSystem

function Test-ThemeReleaseArchive {
    param(
        [Parameter(Mandatory = $true)]
        [string]$ArchivePath
    )

    $archive = [System.IO.Compression.ZipFile]::OpenRead($ArchivePath)
    try {
        $entries = $archive.Entries | Select-Object -ExpandProperty FullName
        $entriesWithBackslash = $entries | Where-Object { $_ -match '\\' }

        if ($entriesWithBackslash.Count -gt 0) {
            throw "Invalid ZIP entry separators found. Use '/' in archive entries only."
        }

        if (-not ($entries -contains 'wp_restatify-base-theme/style.css')) {
            throw "Invalid theme package layout: missing wp_restatify-base-theme/style.css in archive root."
        }
    }
    finally {
        $archive.Dispose()
    }
}

$stagingRoot = Join-Path $tempRoot 'wp_restatify-base-theme'
$files = Get-ChildItem -Path $stagingRoot -Recurse -File

$zipArchive = [System.IO.Compression.ZipFile]::Open($zipPath, [System.IO.Compression.ZipArchiveMode]::Create)
try {
    foreach ($file in $files) {
        $relativePath = $file.FullName.Substring($stagingRoot.Length).TrimStart([char[]]'\\/')
        $entryPath = ('wp_restatify-base-theme/' + $relativePath).Replace('\', '/')
        [System.IO.Compression.ZipFileExtensions]::CreateEntryFromFile(
            $zipArchive,
            $file.FullName,
            $entryPath,
            [System.IO.Compression.CompressionLevel]::Optimal
        ) | Out-Null
    }
}
finally {
    $zipArchive.Dispose()
}

Test-ThemeReleaseArchive -ArchivePath $zipPath

Remove-Item $tempRoot -Recurse -Force

Write-Output "Created release package: $zipPath"
