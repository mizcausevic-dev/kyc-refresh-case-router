$ErrorActionPreference = "Stop"

$root = Split-Path -Parent $PSScriptRoot
$site = Join-Path $root "site"

$expected = @(
    "index.html",
    "kyc-lane\index.html",
    "refresh-queue\index.html",
    "review-posture\index.html",
    "verification\index.html",
    "docs\index.html",
    "robots.txt",
    "sitemap.xml",
    "api\dashboard\summary\index.json",
    "api\kyc-lane.json",
    "api\refresh-queue.json",
    "api\verification.json",
    "api\sample.json"
)

foreach ($relative in $expected) {
    $full = Join-Path $site $relative
    if (-not (Test-Path $full)) {
        throw "Missing expected file: $relative"
    }
}

Write-Output "Smoke check passed for $site"
