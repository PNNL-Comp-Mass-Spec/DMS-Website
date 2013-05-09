# use local configuration settings file, if one is present
$configDefFilePath = "$PSScriptRoot\config-def.psm1"
if($env:LOCALAPPDATA) { 
	$cfp = "{0}\PS_DMS_Scripts\config-def.psm1" -f $env:LOCALAPPDATA
	if(Test-Path -Path $cfp) { $configDefFilePath = $cfp }
}

Import-Module $configDefFilePath
Import-Module $PSScriptRoot\dump_db.psm1

# create timestamp-based root name for local folder names
$rootName = "{0:yyyyMMddhhmm}" -f (get-date)

cls
# will there be any downloading?  If so, import download module and get password
foreach($source in $sources) { 
	if($source["sftpHost"]) {
		Import-Module $PSScriptRoot\download_dbs.psm1
		$password = Read-Host -assecurestring "Please enter your password"
		$userPassword = [System.Runtime.InteropServices.Marshal]::PtrToStringAuto([System.Runtime.InteropServices.Marshal]::SecureStringToBSTR($password))
		break;
	}
}

#process each defined source
$code = ""
$downloads = @{}
foreach($source in $sources) {
	#set up local folders to receive downloaded and copied config db files and dump files
	$localDbFileFolderPath = "{0}\{1}{2}" -f $defaults["localDbFileFolderPath"], $source["version"], $rootName
	if (!(Test-Path -path $localDbFileFolderPath)) { New-Item $localDbFileFolderPath -Type Directory | Out-Null }
	#
	$localDumpFileFolderPath = "{0}\{1}{2}" -f $defaults["localDumpFileFolderPath"], $source["version"], $rootName
	if (!(Test-Path -path $localDumpFileFolderPath)) { New-Item $localDumpFileFolderPath -Type Directory | Out-Null }

	# if source is defined as SFTP, download the *.db files
	# and extract the dump files
	if($source["sftpHost"]) {
		# download config db files from sftp server and generate dump files
		DownloadSftpFiles $source["sftpHost"] $source["userName"] $userPassword $source["remoteDir"] $localDbFileFolderPath
		DumpSqliteDBFiles $localDbFileFolderPath $localDumpFileFolderPath
		$downloads[$source["version"]] = $localDumpFileFolderPath
	}
	# if source is defined as local path, copy the *.db files
	# and extract the dump files
	elseif("sourceFileFolderPath") {
		# copy config db files from code tree and generate dump files
		$sourceFileFolderPath = $source["sourceFileFolderPath"]
		Copy-Item $sourceFileFolderPath\*.db $localDbFileFolderPath
		DumpSqliteDBFiles $localDbFileFolderPath $localDumpFileFolderPath
		$code = $localDumpFileFolderPath
	}
	# delete the *.db files if in same folder as *.sql dump files
	if($localDbFileFolderPath -eq $localDumpFileFolderPath) {
		Get-ChildItem $localDbFileFolderPath -Filter "*.db" | ForEach-Object { 
			$dbFile = "$localDbFileFolderPath\$($_.Name)"
			Remove-Item $dbFile
		}
	}
}

# launch Beyond Compare
if($settings["launchBeyondCompare"]) {
	foreach($key in $downloads.Keys) { 
		& "C:\Program Files (x86)\Beyond Compare 2\bc2.exe" $code $downloads[$key] 
		# Wait 500 msec before continuing to avoid shared resource conflicts
		Start-Sleep -m 500
	}
}
