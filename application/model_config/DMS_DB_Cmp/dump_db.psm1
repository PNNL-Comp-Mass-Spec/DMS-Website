# dump contents of sqlite3 "*.db" files
# in given folder to dump "*.sql" files
# in given output folder (can be same folder)
#
function DumpSqliteDBFiles($localDbFileFolderPath, $localDumpFileFolderPath) {
	$prog = "$PSScriptRoot\sqlite3.exe"

	$sqliteDBs = Get-ChildItem $localDbFileFolderPath -Filter "*.db"

	Write-Output ("Converting {0} .db files to .sql " -f ($sqliteDBs.Length) )
	$filesConverted = 0

	foreach($file in $sqliteDBs) {
		$percentComplete = [math]::Round($filesConverted * 100.0 / $sqliteDBs.Length, 0)
		Write-Progress -Activity "Converting .db files to .sql" -Status "$percentComplete% Complete:" -PercentComplete $percentComplete;

		$dbFile = "$localDbFileFolderPath\$($file.Name)"
		$dumpFile = "$localDumpFileFolderPath\$($file.BaseName).sql"

		echo .dump | &$prog $dbFile > $dumpFile

		$filesConverted = $filesConverted + 1
	}

	Write-Progress -CurrentOperation "Converting .db files to .sql" -Completed "Done"

	Write-Output "See .sql files at $localDumpFileFolderPath"
	Write-Output ""

}
