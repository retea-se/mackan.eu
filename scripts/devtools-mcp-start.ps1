$ErrorActionPreference = 'Stop'

# Starta Chrome med DevTools-port
$chrome = "$Env:ProgramFiles\Google\Chrome\Application\chrome.exe"
if (-not (Test-Path $chrome)) {
  $chrome = "$Env:ProgramFiles(x86)\Google\Chrome\Application\chrome.exe"
}
if (-not (Test-Path $chrome)) {
  Write-Error "Hittade inte Chrome på standardplats. Starta Chrome manuellt med --remote-debugging-port=9222."
}

$userDir = Join-Path $env:TEMP 'chrome-mcp'
New-Item -ItemType Directory -Force -Path $userDir | Out-Null

Start-Process -FilePath $chrome -ArgumentList @("--remote-debugging-port=9222","--user-data-dir=$userDir","--disable-background-networking")

Write-Host "Chrome startat med DevTools-port 9222."
Write-Host "I ett nytt fönster: klona och starta MCP-servern enligt DEVTOOLS_MCP_SETUP.md"

