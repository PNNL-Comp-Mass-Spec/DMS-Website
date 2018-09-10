
# Compares all of the files in local directory, including files in the subdirectory named "Identical"
# to the files in the MasterCode directory, which won't have the Identical subdirectory
#
# For any mis-matching files, copy them to $codeSyncDir
# 

function CompareFilesToMaster($localCodeDir, $masterCodeDir, $codeSyncDir, $maxFilesToCompare) {
    
    Write-Output ""
    Write-Output "Comparing files in $localCodeDir to $masterCodeDir"
    Write-Output "Copying mismatches to $codeSyncDir"

    # Find all of the files in the local code directory
    $localCodeFiles = Get-ChildItem $localCodeDir -File

    # Find all of the files in the subdirectory named "Identical" below $localCodeDir
    # Files were moved to that directory by the CompareDirs function in compare_directories.psm1
    $localIdenticalFilesDir = Join-Path $localCodeDir "Identical"
    if ((Test-Path "$localIdenticalFilesDir")) { 
        $additionalLocalFiles = Get-ChildItem $localIdenticalFilesDir -File
        $localCodeFiles += $additionalLocalFiles
    }

    # Assure that the CodeSync directory is empty
    if (Test-Path "$codeSyncDir") {
        $filesToRemove = Get-ChildItem $codeSyncDir -File
        foreach($file in $filesToRemove) {
            Write-output ("Deleting {0}" -f $file.FullName)
            Remove-Item $file.FullName
        }
        $codeSyncDirValidated = 1
    }

    $filesCompared = 0
    $mismatchedFiles = 0
    $codeSyncDirValidated = 0

    foreach($localfile in $localCodeFiles) {
        $percentComplete = [math]::Round($filesCompared * 100.0 / $localCodeFiles.Length, 0)
        Write-Progress -Activity "Comparing files in $localCodeDir to $masterCodeDir" -Status "$percentComplete% Complete:" -PercentComplete $percentComplete;

        # Write-output "Examining $localfile.Fullname"

        $localHash = (Get-Filehash $localfile.Fullname).Hash

        $comparisonFile = Join-Path $masterCodeDir $localfile.Name

        $filesMatch=1
        if (!(Test-Path "$comparisonFile")) {
            # File not found; this is a mismatch
            Write-output "File not found: $comparisonFile"
            $filesMatch = 0
        } else {
            $comparisonHash = (Get-Filehash $comparisonFile).Hash
    
            if ($localHash -ne $comparisonHash) {
                # File hashes differ
                Write-output "File hashes differ: $localfile"
                $filesMatch = 0
            } 
        }

        if (-not $filesMatch) {

            if (($codeSyncDirValidated -eq 0) -and (!(Test-Path "$codeSyncDir"))) { 
                New-Item $codeSyncDir -Type Directory | Out-Null 
                $codeSyncDirValidated = 1
            }

            $targetFilePath = Join-Path $codeSyncDir $localfile.Name
            Copy-Item $localfile.Fullname $targetFilePath -Force

            $mismatchedFiles = $mismatchedFiles + 1
        }

        $filesCompared = $filesCompared + 1

        if (($maxFilesToCompare -gt 0) -and ($filesCompared -ge $maxFilesToCompare)) {
            break
        }
    }

    if ($mismatchedFiles -eq 0) {
        Write-Output "No mismatched files were found"
    } else {
        Write-Output "Mismatched file count: $mismatchedFiles"
    }

    Write-Progress -CurrentOperation "Comparing files in $localCodeDir to $masterCodeDir" -Completed "Done"

}
