# Non-interactive mail configuration to LOG driver for local dev
# Updates or appends MAIL_* keys in .env

$envPath = Join-Path (Get-Location) ".env"

if (-not (Test-Path $envPath)) {
    Write-Host "❌ .env not found at $envPath" -ForegroundColor Red
    exit 1
}

$content = Get-Content $envPath -Raw

function Set-Or-Append([string]$input, [string]$key, [string]$value) {
    $pattern = "(?m)^$key=.*$"
    if ([regex]::IsMatch($input, $pattern)) {
        return [regex]::Replace($input, $pattern, "$key=$value")
    } else {
        if ($input.Length -gt 0 -and -not $input.EndsWith("`n")) { $input += "`n" }
        return $input + "$key=$value`n"
    }
}

$content = Set-Or-Append $content 'MAIL_MAILER' 'log'
$content = Set-Or-Append $content 'MAIL_HOST' 'localhost'
$content = Set-Or-Append $content 'MAIL_PORT' '1025'
$content = Set-Or-Append $content 'MAIL_USERNAME' ''
$content = Set-Or-Append $content 'MAIL_PASSWORD' ''
$content = Set-Or-Append $content 'MAIL_ENCRYPTION' 'null'
$content = Set-Or-Append $content 'MAIL_FROM_ADDRESS' 'admin@example.com'
$content = Set-Or-Append $content 'MAIL_FROM_NAME' '"CSAR Platform"'

Set-Content -Path $envPath -Value $content -Encoding UTF8
Write-Host "✅ .env mail config set to LOG driver (local dev)." -ForegroundColor Green


