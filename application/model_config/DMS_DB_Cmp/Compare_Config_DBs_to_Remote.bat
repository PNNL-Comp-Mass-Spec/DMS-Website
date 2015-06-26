if not exist C:\Data\Junk mkdir C:\Data\Junk

pushd "F:\My Documents\Scripts\Powershell\DMS_DB_Compare"

PowerShell.exe -ExecutionPolicy Unrestricted -file "F:\My Documents\Scripts\Powershell\DMS_DB_Compare\dump_all.ps1"
pause
