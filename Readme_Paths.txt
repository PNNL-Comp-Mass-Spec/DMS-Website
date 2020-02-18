Location of files on PrismWeb3:
/files1/www/html/dms/
Corresponding Website: https://dms2.pnl.gov/

Location of files on PrismWeb2:
/files1/www/html/dmsdev/
Corresponding Website: https://dmsdev.pnl.gov/

Location of files for DMSBeta (on PrismWeb3):
/files1/www/html/dmsbeta/
Corresponding Website: https://dmsbeta.pnl.gov/

Models, views, and controllers are at:
/files1/www/html/dms/application

DMS 2 Config DBs are at:   /files1/www/html/dms/application/model_config
DMSBeta Config DBs are at: /files1/www/html/dmsbeta/application/model_config



-- Additional files on PrismWeb3 --

Xymon files are at /files0/www/html/prismbb/xymon/server/etc

PrismWiki files are at /files1/www/html/wiki

See also the DB_Updating_Readme.txt file at 
...DMS2/application/model_config/
for info on the SqLite .DB files.

Web log files are at /etc/httpd/logs (which points to /var/log/httpd24 which points to /files1/log/httpd)
	Rotate command is at  /etc/logrotate.d/httpd24-httpd
	The logs are only rotated when apache is restarted:

Contents of /etc/logrotate.d/httpd24-httpd:
/files1/log/httpd/*log /files1/log/httpd/*/*log {
    create
    dateext
    compress
    weekly
    rotate 12
    missingok
    notifempty
    sharedscripts
    minsize 5M
    delaycompress
    postrotate
        /bin/systemctl reload  httpd24-httpd.service > /dev/null 2>/dev/null || true
    endscript
}
