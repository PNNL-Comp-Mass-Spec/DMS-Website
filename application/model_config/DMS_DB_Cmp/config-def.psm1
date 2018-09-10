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
    "dmsDbSqlPath" = "F:\Documents\Projects\DataMining\PRISM_Web_Pages\PrismWeb\DMS2\application\model_config\DMS_DB_Sql";
    "cbdmsDbSqlPath" = "F:\Documents\Projects\DataMining\PRISM_Web_Pages\PrismWeb\DMS2\application\model_config\DMS_DB_Sql\cbdms";
}

$global:sources = @(
    @{
        "version" = "dev";
        "sftpHost" = "prismweb2.pnl.gov";
        "userName" = "d3l243";
        "remoteDir" = "/files1/www/html/dmsdev/application/model_config";
    },
    @{
        "version" = "prod";
        "sftpHost" = "prismweb3.pnl.gov";
        "userName" = "d3l243";
        "remoteDir" = "/files1/www/html/dms/application/model_config";
    },
    @{
        "version" = "cbdmsOnline";
        "sftpHost" = "cbdmsweb.emsl.pnl.gov";
        "userName" = "d3l243";
        "remoteDir" = "/data/www/html/dms/application/model_config/cbdms";
    },
    @{
        "version" = "code";
        "sourceFileFolderPath" = "F:\Documents\Projects\DataMining\PRISM_Web_Pages\PrismWeb\DMS2\application\model_config";
    },
    @{
        "version" = "cbdmsCode";
        "sourceFileFolderPath" = "F:\Documents\Projects\DataMining\PRISM_Web_Pages\PrismWeb\DMS2\application\model_config\cbdms";
    }
)
