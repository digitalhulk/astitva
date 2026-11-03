# Builds a new Astitva release: sets the version, makes both zips and writes update.json.
# Usage:  .\release.ps1 -Version 1.0.1 -Notes "Fixed footer spacing"
#         .\release.ps1 -Version 1.0.1 -Notes "..." -BaseUrl "https://example.com/astitva-updates"
# Then upload the three files from dist\<version> to the place BaseUrl points to.
param(
	[Parameter(Mandatory = $true)][string]$Version,
	[string]$Notes = "",
	[string]$BaseUrl = ""
)
$ErrorActionPreference = "Stop"
if ($Version -notmatch '^\d+\.\d+\.\d+$') { throw "Version must look like 1.0.1" }
$root = Split-Path $PSScriptRoot -Parent
if (-not $BaseUrl) { $BaseUrl = "https://github.com/digitalhulk/astitva/releases/download/v$Version" }
$utf8 = New-Object Text.UTF8Encoding $false

function Set-InFile($path, $pattern, $replacement) {
	$text = [IO.File]::ReadAllText($path)
	if (-not [regex]::IsMatch($text, $pattern)) { throw "Version line not found in $path" }
	$new = [regex]::Replace($text, $pattern, $replacement)
	[IO.File]::WriteAllText($path, $new, $utf8)
}
Set-InFile "$root\astitva\style.css" '(?m)^Version:\s*[\d.]+' "Version: $Version"
Set-InFile "$root\astitva\functions.php" "define\( 'AGI_VERSION', '[\d.]+' \)" "define( 'AGI_VERSION', '$Version' )"
Set-InFile "$root\astitva-core\astitva-core.php" '(?m)^ \* Version:\s*[\d.]+' " * Version: $Version"
Set-InFile "$root\astitva-core\astitva-core.php" "define\( 'AGIC_VERSION', '[\d.]+' \)" "define( 'AGIC_VERSION', '$Version' )"

$leftovers = Get-ChildItem "$root\astitva", "$root\astitva-core" -Recurse -Filter "_tmp*"
if ($leftovers) { throw "Remove test files first: $($leftovers.FullName -join ', ')" }

$out = "$root\dist\$Version"
New-Item -ItemType Directory -Force $out | Out-Null
Remove-Item "$out\*" -ErrorAction SilentlyContinue
Compress-Archive -Path "$root\astitva" -DestinationPath "$out\astitva.zip"
Compress-Archive -Path "$root\astitva-core" -DestinationPath "$out\astitva-core.zip"

$changelog = if ($Notes) { "<ul><li>" + [Net.WebUtility]::HtmlEncode($Notes) + "</li></ul>" } else { "" }
$feed = [ordered]@{
	theme  = [ordered]@{ version = $Version; package = "$BaseUrl/astitva.zip"; url = $BaseUrl; requires = "6.5"; requires_php = "8.1" }
	plugin = [ordered]@{ version = $Version; package = "$BaseUrl/astitva-core.zip"; url = $BaseUrl; requires = "6.5"; requires_php = "8.1"; tested = "6.9"; changelog = $changelog }
}
[IO.File]::WriteAllText("$out\update.json", ($feed | ConvertTo-Json -Depth 4), $utf8)
Copy-Item "$out\astitva.zip", "$out\astitva-core.zip" "$root\dist\" -Force
Get-ChildItem $out | ForEach-Object { "{0}  {1} KB" -f $_.Name, [int]($_.Length / 1KB) }
"Upload these to $BaseUrl, and put update.json where the sites read it."
