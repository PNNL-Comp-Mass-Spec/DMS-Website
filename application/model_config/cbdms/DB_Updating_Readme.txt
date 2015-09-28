These are SqLite DBs.  Suggested Windows program for editing these files: SqLiteMan
Download link: http://sqliteman.com/

For DMS2, connect to PrismWeb via FTP, then go to: 
	/home/prismweb/dms2/system/application/model_config/
and download the .DB file that corresponds to the page family you wish to update

For DMSDev.pnl.gov, connect to PrismWebDev via FTP, then go to:
	/home/prismweb/dms2/system/application/model_config
and download the .DB file that corresponds to the page family you wish to update

Open the .DBfile using SqLiteMan

Double click the appropriate table and its contents should appear in the middle right.
To change a cell, double click the cell, type the value, and press Enter (or Esc to cancel)
To save changes to a table, you must click "Commit the current transaction" 
(4th button from the left in the ribbon bar).

Once done, exit SqLiteMan and FTP the file back to the server.  
Be sure to tell Gary you did this so that the changes can find their way into the repository.


To compare the version of a given DB on PrismWeb vs. PrismWebDev, use the /spec/dump command
(also potentially useful is the /spec/config_info/ command)

To view the contents of all Config DBs:
http://dms2.pnl.gov/spec/dump
 vs.
http://dmsdev.pnl.gov/spec/dump


To filter by .db file name, append text after dump (will match text anywhere in the .DB file name):
http://dms2.pnl.gov/spec/dump/class
	matches instrumentclass.db
http://dms2.pnl.gov/spec/dump/dataset
	matches data_package_dataset.db, dataset.db, dataset_disposition.db, etc.
	
To filter by table name within the databases, append another segment to the url:
http://dmsdev.pnl.gov/spec/dump/db/general
	matches the "general_params" table in all the databases (since every db ends in '.db' extension)
http://dmsdev.pnl.gov/spec/dump/analysis/form
	matches all the tables with "form" in their names and all the databases with "analysis" in their names.

The old way, useful only for a single .DB file, is to use /spec/config_info
To retrieve tab delimited text, use:
	http://dms2.pnl.gov/spec/config_info/data_package

To retrieve html formatted text, use:
	http://dms2.pnl.gov/spec/config_info/data_package/html


Custom / Ad-hoc list report pages can be browsed using:
	http://dms2.pnl.gov/data/lr_menu

To edit the ad-hoc reports, click the "Config db" link or go to:
	http://dms2.pnl.gov/config_db/show_db/ad_hoc_query.db or
	http://dmsdev.pnl.gov/config_db/show_db/ad_hoc_query.db