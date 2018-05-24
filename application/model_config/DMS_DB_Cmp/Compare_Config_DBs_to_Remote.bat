if not exist C:\Data\Junk mkdir C:\Data\Junk

pushd "F:\Documents\Scripts\Powershell\DMS_DB_Compare"

PowerShell.exe -ExecutionPolicy RemoteSigned -file "F:\Documents\Scripts\Powershell\DMS_DB_Compare\dump_all.ps1"
pause
