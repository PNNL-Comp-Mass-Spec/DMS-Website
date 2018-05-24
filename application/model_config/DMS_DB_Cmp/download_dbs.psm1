# Old tool:
# Add-PSSnapin KTools.PowerShell.SFTP  # available http://kevinrr3.blogspot.com/2013/02/sftp-in-powershell.html

# In May 2018 switched to Posh-SSH
# To install, run this from an admin level powershell window
# Install-Module -Name Posh-SSH
#
# To manually load and test, use
# Set-ExecutionPolicy RemoteSigned -Scope Process -force
# Import-Module Posh-SSH

function DownloadSftpFiles($sftpHost, $userName, $userPassword, $remoteDir, $localDbFileFolderPath, $maxFilesToDownload) {

	Import-Module Posh-SSH

	# See available commands with
	# Get-Command -Module Posh-SSH

	Write-Output ""
	Write-Output "Connecting to $sftpHost as user $userName"

	# Establish the credentials
	$sftpPassword = ConvertTo-SecureString $userPassword -AsPlainText -Force

	$credentials = New-Object System.Management.Automation.PSCredential ($userName, $sftpPassword)

	# This works, but it also retrieves files from subdirectories, which we don't want
	#Get-SCPFolder -ComputerName $sftpHost -Credential $credentials -RemoteFolder $remoteDir -LocalFolder $localDbFileFolderPath -NoProgress

	# Open the SFTP connection
	$sftpSession = New-SFTPSession -ComputerName $sftpHost -Credential $credentials

	Write-Output "Finding files at $remoteDir"
	$remoteFiles = Get-SFTPChildItem -SessionId ($sftpSession).SessionId -Path $remoteDir

	if(!(test-path $localDbFileFolderPath)) {
		New-Item -ItemType Directory -Force -Path $localDbFileFolderPath | Out-Null
	}
	
	Write-Output "Downloading .db files from $sftpHost"

	$filesDownloaded = 0

	foreach($file in $remoteFiles) {
		if ($file.Name.EndsWith(".db", "OrdinalIgnoreCase")) {
			$percentComplete = [math]::Round($filesDownloaded * 100.0 / $remoteFiles.Length, 0)
			Write-Progress -Activity "Downloading .db files from $sftpHost" -Status "$percentComplete% Complete:" -PercentComplete $percentComplete;

			# Delete the local file if it already exists
			$localFilePath = Join-Path $localDbFileFolderPath $file.Name
			if (test-path $localFilePath) {
				Remove-item $localFilePath
			}
			Get-SFTPFile -SessionId ($sftpSession).SessionId -RemoteFile $file.FullName -LocalPath $localDbFileFolderPath

			$filesDownloaded = $filesDownloaded + 1

			if (($maxFilesToDownload -gt 0) -and ($filesDownloaded -ge $maxFilesToDownload)) {
				break
			}
		}
	}

	Write-Progress -CurrentOperation "Downloading .db files" -Completed "Done"

	#Close the SFTP connection
	Get-SFTPSession | % { Remove-SFTPSession -SessionId ($_.SessionId) }  | Out-Null
}