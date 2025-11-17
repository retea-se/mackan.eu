$ErrorActionPreference = 'Stop'

$root = Split-Path -Parent $MyInvocation.MyCommand.Path
$devtools = Join-Path $root '..\devtools'
$mcpRepo = Join-Path $devtools 'chrome-devtools-mcp'

if (-not (Test-Path $mcpRepo)) {
  Write-Error "Hittar inte repo: $mcpRepo. Kör git clone enligt DEVTOOLS_MCP_SETUP.md."
}

# Starta Chrome med DevTools-port
$chrome = "$Env:ProgramFiles\Google\Chrome\Application\chrome.exe"
if (-not (Test-Path $chrome)) { $chrome = "$Env:ProgramFiles(x86)\Google\Chrome\Application\chrome.exe" }
if (-not (Test-Path $chrome)) { Write-Error "Chrome saknas. Installera Chrome först." }

$userDir = Join-Path $env:TEMP 'chrome-mcp'
New-Item -ItemType Directory -Force -Path $userDir | Out-Null
Start-Process -FilePath $chrome -ArgumentList @('--remote-debugging-port=9222',"--user-data-dir=$userDir") | Out-Null
Write-Host "Chrome startat på devtools-port 9222."

# Starta MCP-servern
Push-Location $mcpRepo
npm start
Pop-Location

