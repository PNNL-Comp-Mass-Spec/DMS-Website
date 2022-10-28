# This function dumps contents of sqlite3 "*.db" files in given folder 
# to "*.sql" files in given output folder (can be same folder)

# Steps to manually export the contents of a SQLite .db file to a .sql text file (from https://database.guide/export-entire-sqlite-database-to-sql-file/)
#
# Start sqlite3.exe, providing the path to the .db file
# Use the ".once" command, specifying the path to the filename to store the results of the next command to
# Use the ".dump" command
# Exit with ".quit"

function DumpSqliteDBFiles($localDbFileFolderPath, $localDumpFileFolderPath) {
    $prog = "$PSScriptRoot\sqlite3.exe"

    $sqliteDBs = Get-ChildItem $localDbFileFolderPath -Filter "*.db"

    Write-Output ("Converting {0} .db files to .sql" -f ($sqliteDBs.Length) )

    $filesConverted = 0

    foreach($file in $sqliteDBs) {
        $percentComplete = [math]::Round($filesConverted * 100.0 / $sqliteDBs.Length, 0)
        Write-Progress -Activity "Converting .db files to .sql" -Status "$percentComplete% Complete:" -PercentComplete $percentComplete;

        $dbFile = "$localDbFileFolderPath\$($file.Name)"
        $dumpFile = "$localDumpFileFolderPath\$($file.BaseName).sql"

        # Start sqlite3.exe, passing the path to the .db file
        # Initiate the ".dump" command, redirecting the output to the .sql file defined by $dumpFile
        # See above for steps to manually dump the contents of a SQLite .db file to a .sql file

        echo .dump | &$prog $dbFile | Out-File -FilePath $dumpFile -Encoding utf8

        $filesConverted = $filesConverted + 1
    }

    Write-Progress -CurrentOperation "Converting .db files to .sql" -Completed "Done"

    Write-Output "See .sql files at $localDumpFileFolderPath"
    Write-Output ""
}
