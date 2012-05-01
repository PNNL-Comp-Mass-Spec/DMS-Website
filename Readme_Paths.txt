Location of files on PrismWeb:
/home/prismweb/dms2
Corresponding Website: http://dms2.pnl.gov/

Location of files on PrismWebDev:
/home/prismweb/dms2
Corresponding Website: http://dmsdev.pnl.gov/

Location of files for DMSBeta (on PrismWeb):
/home/prismweb/dms2beta/
Corresponding Website: http://dmsbeta.pnl.gov/

Models, views, and controllers are at:
/home/prismweb/dms2/system/application

DMS 2 Config DBs are at:   /home/prismweb/dms2/system/application/model_config
DMSBeta Config DBs are at: /home/prismweb/dms2beta/system/application/model_config



-- Additional files on PrismWeb --

Hobbit files are at '/opt/hobbit/server/etc'

PrismWiki files are at /storage/www/prismwiki

See also the DB_Updating_Readme.txt file at 
...DMS2/system/application/model_config/
for info on the SqLite .DB files.

Web log files are at /etc/httpd/logs (which points to /var/log/httpd)
	Rotate command is at  /etc/logrotate.d/httpd

	yesterday=ac$(date -r access_log.1 +%Y-%m-%d_%H%M).log;
	logdir=/var/log/httpd;
	cp ${logdir}/access_log.1 ${logdir}/archived/${yesterday};
	chmod 775 ${logdir}/archived/${yesterday};
	chgrp apache ${logdir}/archived/${yesterday};
