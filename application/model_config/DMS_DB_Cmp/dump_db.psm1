# dump contents of sqlite3 "*.db" files
# in given folder to dump "*.sql" files
# in given output folder (can be same folder)
#
function DumpSqliteDBFiles($localDbFileFolderPath, $localDumpFileFolderPath) {
	$prog = "$PSScriptRoot\sqlite3.exe"
	Get-ChildItem $localDbFileFolderPath -Filter "*.db" | ForEach-Object {
		$dbFile = "$localDbFileFolderPath\$($_.Name)"
		$dumpFile = "$localDumpFileFolderPath\$($_.BaseName).sql"
		Write-Host "$dbFile -> $dumpFile"
		echo .dump | &$prog $dbFile > $dumpFile
	}
}
