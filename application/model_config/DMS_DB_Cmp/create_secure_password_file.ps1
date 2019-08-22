# Use this script to create a file named cred_Username.txt with a password stored as a secure string
# This string can only be decrypted by a process running as the same user that ran this script to create the file
# See https://www.red-gate.com/simple-talk/sysadmin/powershell/powershell-and-secure-strings/

$localFilePath='.'
$outFilePath = "$localFilePath\cred_$env:UserName.txt"

write-host "Enter the password to convert to a secure string"
read-host -AsSecureString | ConvertFrom-SecureString | Out-File $outFilePath

write-host "Created file $outFilePath"
