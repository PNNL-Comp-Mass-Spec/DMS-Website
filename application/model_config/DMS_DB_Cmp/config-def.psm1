# Customize this file and place in your LocalAppData folder
# For example, C:\Users\user1234\AppData\Local\PS_DMS_Scripts
#
# In order for powershell to be able to access this file, you must either digitally sign it or change the execution policy to unrestricted:
#    powershell set-executionpolicy unrestricted

$global:settings = @{
	"launchBeyondCompare" = $true
}

$global:defaults = @{
	"localDbFileFolderPath" = "C:\Data\Junk";
	"localDumpFileFolderPath" = "C:\Data\Junk";
}

$global:sources = @(
	@{
		"version" = "dev";
		"sftpHost" = "prismwebdev2.pnl.gov";
		"userName" = "d3j410";
		"remoteDir" = "/storage/www/html/dms2/application/model_config";
	},
	@{
		"version" = "prod";
		"sftpHost" = "prismweb2.emsl.pnl.gov";
		"userName" = "d3j410";
		"remoteDir" = "/var/www/html/dms/application/model_config";
	},
	@{
		"version" = "code";
		"sourceFileFolderPath" = "C:\Users\d3j410\Documents\Aptana3Workspace\DMS_Website\application\model_config";
	}
)