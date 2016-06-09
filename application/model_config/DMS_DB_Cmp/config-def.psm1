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
		"userName" = "d3l243";
		"remoteDir" = "/file1/www/html/dmsdev/application/model_config";
	},
	@{
		"version" = "prod";
		"sftpHost" = "prismweb2.emsl.pnl.gov";
		"userName" = "d3l243";
		"remoteDir" = "/file1/www/html/dms/application/model_config";
	},
	@{
		"version" = "cbdmsOnline";
		"sftpHost" = "cbdmsweb.emsl.pnl.gov";
		"userName" = "d3l243";
		"remoteDir" = "/data/www/html/dms/application/model_config/cbdms";
	},
	@{
		"version" = "code";
		"sourceFileFolderPath" = "F:\My Documents\Projects\DataMining\PRISM_Web_Pages\PrismWeb\DMS2\application\model_config";
	},
	@{
		"version" = "cbdmsCode";
		"sourceFileFolderPath" = "F:\My Documents\Projects\DataMining\PRISM_Web_Pages\PrismWeb\DMS2\application\model_config\cbdms";
	}
)