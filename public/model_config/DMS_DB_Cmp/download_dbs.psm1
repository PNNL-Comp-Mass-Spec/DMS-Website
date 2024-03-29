﻿# Old tool:
# Add-PSSnapin KTools.PowerShell.SFTP  # available http://kevinrr3.blogspot.com/2013/02/sftp-in-powershell.html

# In May 2018 switched to Posh-SSH
# To install, run this from an admin level powershell window
# Install-Module -Name Posh-SSH
#
# To manually load and test, use
# Set-ExecutionPolicy RemoteSigned -Scope Process -force
# Import-Module Posh-SSH
#
# List commands:
# Get-Command -Module Posh-SSH
#
#########################################
# Download files from the given host
# Returns the number of files downloaded
#########################################
function DownloadSftpFiles($sftpHost, $userName, $userPassword, $remoteDir, $localDbFileFolderPath, $maxFilesToDownload) {

    Import-Module Posh-SSH

    # See available commands with
    # Get-Command -Module Posh-SSH

    Write-Host ""
    Write-Host "Connecting to $sftpHost as user $userName"

    # Establish the credentials

    $sftpPassword = ConvertTo-SecureString $userPassword -AsPlainText -Force
    $credentials = New-Object System.Management.Automation.PSCredential ($userName, $sftpPassword)

    # This works, but it also retrieves files from subdirectories, which we don't want
    # Get-SCPFolder -ComputerName $sftpHost -Credential $credentials -RemoteFolder $remoteDir -LocalFolder $localDbFileFolderPath -NoProgress

    # Open the SFTP connection (for more info, use -Verbose)
    # If the RSA key of the remote host changes, delete the old entry in the registry at HKCU\Software\PoshSSH
    $sftpSession = New-SFTPSession -ComputerName $sftpHost -Credential $credentials

    if ($sftpSession -eq $null) {
        Write-Host "Connection failed; aborting"
        return 0
    }

    Write-Host "Finding files at $remoteDir"
    $remoteFiles = Get-SFTPChildItem -SessionId ($sftpSession).SessionId -Path $remoteDir

    if(!(test-path $localDbFileFolderPath)) {
        New-Item -ItemType Directory -Force -Path $localDbFileFolderPath | Out-Null
    }
    
    Write-Host "Downloading .db files from $sftpHost"

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
            
            # Format for Posh-SSH 2.3.0
            # Get-SFTPFile -SessionId ($sftpSession).SessionId -RemoteFile $file.FullName -LocalPath $localDbFileFolderPath

            # Format for Posh-SSH 3.0.0            
            Get-SFTPItem -SessionId ($sftpSession).SessionId -Path $file.FullName -Destination $localDbFileFolderPath

            $filesDownloaded = $filesDownloaded + 1

            if (($maxFilesToDownload -gt 0) -and ($filesDownloaded -ge $maxFilesToDownload)) {
                break
            }
        }
    }

    Write-Progress -CurrentOperation "Downloading .db files" -Completed "Done"

    # Close the SFTP connection
    Remove-SFTPSession -SessionId $sftpSession.SessionId
    
    return $filesDownloaded 
}
