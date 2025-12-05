# Set local dev environment in .env (non-interactive)

$envPath = Join-Path (Get-Location) ".env"
if (-not (Test-Path $envPath)) { Write-Host "❌ .env not found" -ForegroundColor Red; exit 1 }

$c = Get-Content $envPath -Raw

function SetOrAppend([string]$input,[string]$key,[string]$value){
  $pattern = "(?m)^$key=.*$"
  if([regex]::IsMatch($input,$pattern)){ return [regex]::Replace($input,$pattern,"$key=$value") }
  if($input.Length -gt 0 -and -not $input.EndsWith("`n")){ $input+="`n" }
  return $input+="$key=$value`n"
}

$c = SetOrAppend $c 'APP_ENV' 'local'
$c = SetOrAppend $c 'APP_DEBUG' 'true'
$c = SetOrAppend $c 'APP_URL' 'http://127.0.0.1:8000'
$c = SetOrAppend $c 'SESSION_DRIVER' 'file'
$c = SetOrAppend $c 'CACHE_STORE' 'file'
$c = SetOrAppend $c 'QUEUE_CONNECTION' 'sync'

Set-Content -Path $envPath -Value $c -Encoding UTF8
Write-Host "✅ Local dev env set (SESSION_DRIVER=file, CACHE_STORE=file, APP_ENV=local)." -ForegroundColor Green


