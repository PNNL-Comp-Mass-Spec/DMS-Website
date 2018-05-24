
# Compares all of the files in the base directory to other directories
# If a file is identical in all of the directories, it is moved into a subdirectory named Identical
# in each of the directories being compared

function CompareDirs($baseDir, $comparisonDirs) {
	
	Write-Output ""
	Write-Output "Comparing files in $baseDir to $comparisonDirs"

	# Find all of the files in the base directory
	$baseDirFiles = Get-ChildItem $baseDir -File
	$filesCompared = 0

	$baseIdenticalFilesDir = Join-Path $baseDir "Identical"
	if (!(Test-Path "$baseIdenticalFilesDir")) { 
		New-Item $baseIdenticalFilesDir -Type Directory | Out-Null 
	}

	foreach($comparisonDir in $comparisonDirs) {
		$identicalFilesDir = Join-Path $comparisonDir "Identical"
		if (!(Test-Path "$identicalFilesDir")) { 
			New-Item $identicalFilesDir -Type Directory | Out-Null 
		}
	}

	foreach($baseFile in $baseDirFiles) {
		$percentComplete = [math]::Round($filesCompared * 100.0 / $baseDirFiles.Length, 0)
		Write-Progress -Activity "Comparing files in $baseDir to comparison directories" -Status "$percentComplete% Complete:" -PercentComplete $percentComplete;

		$baseHash = (Get-Filehash $baseFile.Fullname).Hash

		$misMatchCount = 0
		$filesToMove = @{}

		foreach($comparisonDir in $comparisonDirs) {
			$comparisonFile = Join-Path $comparisonDir $baseFile.Name

			if (!(Test-Path "$comparisonFile")) {
				# Write-Output "  file not found: $comparisonFile"
				$misMatchCount = $misMatchCount + 1
				continue
			}

			$comparisonHash = (Get-Filehash $comparisonFile).Hash

			if ($baseHash -eq $comparisonHash) {
				$identicalFilesDir = Join-Path $comparisonDir "Identical"
				$targetFilePath = Join-Path $identicalFilesDir $baseFile.Name

				# Write-Output "  file matches base: $comparisonFile"
				$filesToMove.add($comparisonFile, $targetFilePath)
			} else {
				# Write-Output "  file mismatch: $comparisonFile"
				$misMatchCount = $misMatchCount + 1
			}
		}

		if (($filesToMove.Length -gt 0) -and ($misMatchCount -eq 0)) {

			# Move the files into the Identical directory of each subdirectory
			foreach($fileToMove in $filesToMove.Keys) {
				$targetFilePath = $filesToMove[$fileToMove]
				# Write-Output "  Moving $fileToMove to $targetFilePath"
				Move-Item $fileToMove $targetFilePath
			}

			# Also move the file in the base directory
			$targetFilePath = Join-Path $baseIdenticalFilesDir $baseFile.Name
			# Write-Output ("  Moving {0} to $targetFilePath" -f $baseFile.FullName)
			Move-Item $baseFile.FullName $targetFilePath

		}

		$filesCompared = $filesCompared + 1

	}

	Write-Progress -CurrentOperation "Comparing files in $baseDir to comparison directories" -Completed "Done"

}
