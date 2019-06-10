# This script uses Posh-SSH
# To install, run this from an admin level powershell window
# Install-Module -Name Posh-SSH
#
#
# To use this script, you must enable script execution
# It's safer to digitally sign this script, then use the RemoteSigned policy
#    powershell Set-ExecutionPolicy RemoteSigned
#
# However, signing the script takes some extra work; for details, see:
#    powershell help about_signing
#
# The easier method is to set the execution policy to unrestricted:
#    powershell Set-ExecutionPolicy unrestricted


# Use a local configuration settings file, if one is present
# Example path: C:\Users\d3l243\AppData\Local\PS_DMS_Scripts\config-def.psm1
#
$configDefFilePath = Join-Path $PSScriptRoot "config-def.psm1"
if($env:LOCALAPPDATA) { 
    # Look for config file C:\Users\<username>\AppData\Local\PS_DMS_Scripts\config-def.psm1
    $cfp = Join-Path $env:LOCALAPPDATA (Join-Path "PS_DMS_Scripts" "config-def.psm1")
    if(Test-Path "$cfp") { 
        # Config file found; use it
        $configDefFilePath = $cfp 
    }
}

# Set this to a non-zero value to only process the specified number of files
$maxFilesToDownload=0

# Set this to a timestamp (e.g. 201805220555) to use files in existing directories
$timestampOverride=""

Import-Module Posh-SSH

Write-Output "Loading config options from $configDefFilePath"
Import-Module $configDefFilePath

Import-Module .\dump_db.psm1
Import-Module .\compare_directories.psm1
Import-Module .\compare_files_to_master.psm1

# create timestamp-based root name for local folder names
if ($timestampOverride) {
    $rootName = $timestampOverride
} else {
    $rootName = ("{0:yyyyMMddhhmm}" -f (get-date))
    
    # will there be any downloading?  If so, import download module and get password
    foreach($source in $sources) { 
        if($source["sftpHost"]) {
            Import-Module .\download_dbs.psm1
            $password = Read-Host -assecurestring "Please enter your password"
            $userPassword = [System.Runtime.InteropServices.Marshal]::PtrToStringAuto([System.Runtime.InteropServices.Marshal]::SecureStringToBSTR($password))
            break;
        }
    }
}

# Process each defined source
$code = ""
$cbdmsCode = ""
$downloads = @{}
$devSqlPath = ""
$prodSqlPath = ""
$cbdmsSqlPath = ""

foreach($source in $sources) {
    #set up local folders to receive downloaded and copied config db files and dump files
    $localDbFileFolderPath = Join-Path $defaults["localDbFileFolderPath"] ("{0}{1}" -f $source["version"], $rootName)
    if ( ($timestampOverride) -or (Test-Path "$localDbFileFolderPath")) {
        $skipDownload = 1
    } else { 
        $skipDownload = 0
        New-Item $localDbFileFolderPath -Type Directory | Out-Null 
    }

    $localDumpFileFolderPath = Join-Path $defaults["localDumpFileFolderPath"] ("{0}{1}" -f $source["version"], $rootName)
    if ( (Test-Path "$localDumpFileFolderPath")) {
        if ($timestampOverride) { $skipDump = 1} else { $skipDump = 0 }
    } else { 
        $skipDump = 0
        New-Item $localDumpFileFolderPath -Type Directory | Out-Null 
    }

    # If source is defined as SFTP, download the *.db files
    # and extract the dump files
    if($source["sftpHost"]) {
        # download config db files from sftp server and generate dump files
        if (!($skipDownload)) {
            $filesDownloaded = DownloadSftpFiles $source["sftpHost"] $source["userName"] $userPassword $source["remoteDir"] $localDbFileFolderPath $maxFilesToDownload
            if ($filesDownloaded -eq 0) {
                Write-Output "DownloadSftpFiles did not download any files; skipping host $sftpHost"
                return
            }
        }

        if (!($skipDump)) {
            DumpSqliteDBFiles $localDbFileFolderPath $localDumpFileFolderPath
        }
        $downloads[$source["version"]] = $localDumpFileFolderPath

        if ($source["version"] -eq "dev") {
            $devSqlPath = $localDumpFileFolderPath
        }

        if ($source["version"] -eq "prod") {
            $prodSqlPath = $localDumpFileFolderPath
        }

        if ($source["version"] -eq "cbdmsOnline") {
            $cbdmsSqlPath = $localDumpFileFolderPath
        }

    }

    # If source is defined as local path, copy the *.db files
    # and extract the dump files
    elseif($source["sourceFileFolderPath"]) {
        # copy config db files from code tree and generate dump files
        $sourceFileFolderPath = $source["sourceFileFolderPath"]

        Write-Output "Copying .db files from $sourceFileFolderPath to $localDbFileFolderPath"

        if (!($skipDownload)) {
            $localDbFilesSourcePath = Join-Path $sourceFileFolderPath "*.db"
            if ($maxFilesToDownload -eq 0) {
                Copy-Item $localDbFilesSourcePath $localDbFileFolderPath
            } else {
                $localDbFiles = Get-ChildItem $localDbFilesSourcePath -File | select-object -first $maxFilesToDownload
                foreach ($sourceLocalDbFile in $localDbFiles) {
                    $targetLocalDbFile = Join-Path $localDbFileFolderPath $sourceLocalDbFile.Name
                    Copy-Item $sourceLocalDbFile $targetLocalDbFile
                }
            }
        }

        if (!($skipDump)) {
            DumpSqliteDBFiles $localDbFileFolderPath $localDumpFileFolderPath
        }

        if($source["version"] -eq "cbdmsCode") {
            $cbdmsCode = $localDumpFileFolderPath
        } else {
            $code = $localDumpFileFolderPath
        }

    }

    if (!($skipDownload)) {
        # delete the *.db files if in same folder as the *.sql dump files
        if($localDbFileFolderPath -eq $localDumpFileFolderPath) {
            Get-ChildItem $localDbFileFolderPath -Filter "*.db" | ForEach-Object { 
                $dbFile = Join-Path $localDbFileFolderPath ($_.Name)
                Remove-Item $dbFile
            }
        }
    }
}

if (!($code)) {
    Write-Output '$code is undefined; cannot continue'
    exit
}

# Compare each .sql file in the code directory (the directory with local .db files) to the dev and prod directory
# Move any files that match all three locations into a subdirectory named Identical

$baseSqlDir = $code
$comparisonSqlDirs = @($devSqlPath, $prodSqlPath)

# Example dirs
# C:\Data\Junk\code201805170641 vs. dev201805170641 and prod201805170641
CompareDirs $baseSqlDir $comparisonSqlDirs

# Do the same for the cbdmsCode and cbdmsOnline directories (local CBDMS .db files vs. remote .db files)

$cbdmsBaseSqlDir = $cbdmsCode
$comparisonSqlDirs = @($cbdmsSqlPath)

# Example dirs
# C:\Data\Junk\cbdmsCode201805170641 vs. cbdmsOnline201805170641
CompareDirs $cbdmsBaseSqlDir $comparisonSqlDirs

if ($defaults.Contains("dmsDbSqlPath")) {
    # Compare all of the .sql files below the code directory (including those in Identical)
    # to the .sql files in $defaults["dmsDbSqlPath"]
    # For any mis-matching files, copy them to C:\Data\Junk\CodeSyncyyyymmddhhMM
    
    $codeSyncDir = Join-Path $defaults["localDumpFileFolderPath"] "CodeSync$rootName"
    $dmsDbSqlDir = $defaults["dmsDbSqlPath"]

    CompareFilesToMaster $code $dmsDbSqlDir $codeSyncDir $maxFilesToDownload
    
}

if ($defaults.Contains("cbdmsDbSqlPath")) {
    # Compare all of the .sql files below the cbdms code directory (including those in Identical)
    # to the .sql files in $defaults["cbdmsDbSqlPath"]
    # For any mis-matching files, copy them to C:\Data\Junk\CodeSyncCbdmsyyyymmddhhMM
    
    $codeSyncCbdmsDir = Join-Path $defaults["localDumpFileFolderPath"] "CodeSyncCbdms$rootName"
    $cbdmsDbSqlDir = $defaults["cbdmsDbSqlPath"]

    CompareFilesToMaster $cbdmsCode $cbdmsDbSqlDir $codeSyncCbdmsDir $maxFilesToDownload
    
}

# launch Beyond Compare
if($settings["launchBeyondCompare"]) {

    $beyondComparePath = "C:\Program Files\Beyond Compare 4\bcomp.exe"

    if (!(Test-Path "$beyondComparePath")) { 
        $beyondComparePath = "C:\Program Files (x86)\Beyond Compare 2\bc2.exe" 
    }

    Write-output ""

    if (!(Test-Path "$beyondComparePath")) { 
        Write-Output "Could not find Beyond Compare 4 or Beyond Compare 2" 
    } else {

        if (($codeSyncDir) -and (Test-Path "$codeSyncDir")) {
            Write-output ""
            Write-output "Show BC4 for $codeSyncDir"
            Write-output "         vs. $dmsDbSqlDir"

            & $beyondComparePath $codeSyncDir $dmsDbSqlDir
            Start-Sleep -m 500
        }

        if (($codeSyncCbdmsDir) -and (Test-Path "$codeSyncCbdmsDir")) {
            Write-output ""
            Write-output "Show BC4 for $codeSyncCbdmsDir"
            Write-output "         vs. $cbdmsDbSqlDir"

            & $beyondComparePath $codeSyncCbdmsDir $cbdmsDbSqlDir
            Start-Sleep -m 500
        }

        foreach($key in $downloads.Keys) {
            Write-output ""
            Write-output "Show BC4 for $key"

            if ($key -eq "cbdmsOnline") {
                $leftComparisonDir = $cbdmsCode
            } else {
                $leftComparisonDir = $code
            }
            $rightComparisonDir = $downloads[$key]

            Write-output "             $leftComparisonDir"
            Write-output "         vs. $rightComparisonDir"

            & $beyondComparePath $leftComparisonDir $rightComparisonDir

            # Wait 500 msec before continuing to avoid shared resource conflicts
            Start-Sleep -m 500

        }
    }
}
