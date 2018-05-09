Location of files on PrismWeb:
/file1/www/html/dms/
Corresponding Website: http://dms2.pnl.gov/

Location of files on PrismWebDev:
/file1/www/html/dmsdev/
Corresponding Website: http://dmsdev.pnl.gov/

Location of files for DMSBeta (on PrismWeb):
/file1/www/html/dmsbeta/
Corresponding Website: http://dmsbeta.pnl.gov/

Models, views, and controllers are at:
/file1/www/html/dms/application

DMS 2 Config DBs are at:   /file1/www/html/dms/application/model_config
DMSBeta Config DBs are at: /file1/www/html/dmsbeta/application/model_config



-- Additional files on PrismWeb --

Xymon files are at /file1/www/html/prismbb/xymon/server/etc

PrismWiki files are at /file1/www/html/prismwiki

See also the DB_Updating_Readme.txt file at 
...DMS2/application/model_config/
for info on the SqLite .DB files.

Web log files are at /etc/httpd/logs (which points to /var/log/httpd which points to /file1/log/httpd)
	Rotate command is at  /etc/logrotate.d/httpd
	The logs are only rotated when apache is restarted:

/var/log/httpd/*log {
    missingok
    notifempty
    sharedscripts
    delaycompress
    postrotate
        /sbin/service httpd reload > /dev/null 2>/dev/null || true
    endscript
}
