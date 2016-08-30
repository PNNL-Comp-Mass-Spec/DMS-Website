These are SqLite DBs.  

== Database Editing ==

The old method for editing these files was to use SqLiteMan
	Download link: http://sqliteman.com/
	
	For DMS2, connect to PrismWeb2 via SFTP, then go to: 
		/file1/www/html/dms/application/model_config/
	and download the .DB file that corresponds to the page family you wish to update
	
	For DMSDev.pnl.gov, connect to PrismWebDev2 via SFTP, then go to:
		/file1/www/html/dmsdev/application/model_config
	and download the .DB file that corresponds to the page family you wish to update
	
	Open the .DBfile using SqLiteMan
	
	Double click the appropriate table and its contents should appear in the middle right.
	To change a cell, double click the cell, type the value, and press Enter (or Esc to cancel)
	To save changes to a table, you must click "Commit the current transaction" 
	(4th button from the left in the ribbon bar).
	
	Once done, exit SqLiteMan and FTP the file back to the server.  

The new method for editing these files is to use the website, for example:
http://dmsdev.pnl.gov/config_db/show_db/analysis_job.db

After making a change, SFTP the file from PrismWebDev2 to your computer, then SFTP to PrismWeb2.

== Comparing Dev to Production ==

To compare the version of a given DB on PrismWeb vs. PrismWebDev, use the
Powershell script that downloads each SQLite database, then exports the contents using sqlite3.exe

For more information, see https://stash.pnnl.gov/projects/OMCI/repos/data_automation-scripts/browse/Powershell/DMS_DB_Compare

== Ad-hoc list Reports ==

Custom / Ad-hoc list report pages can be browsed using:
	http://dms2.pnl.gov/data/lr_menu

To edit the ad-hoc reports, click the "Config db" link or go to:
	http://dmsdev.pnl.gov/config_db/show_db/ad_hoc_query.db

== New Page Families ==

The following is from http://prismwiki.pnl.gov/wiki/DMS_Config_DB_Help#Creating_a_Page_Family


It is entirely possible to scratch-build a working page family entirely from the config db editing pages. Use these steps to create a and configure a new page family: 

=== Permissions  ===

Note that permissions on the newly created database could be set to RW for the apache user but not readable by anybody else. For this reason, if you are not an Admin on PrismWebDev, you should first download a simple page family config DB file from PrismWebDev (/home/prismweb/dms2/system/application/model_config/), rename the file to the new page family name, then upload back to PrismWebDev. Next, continue at step 2 below 

=== Create the Config DB  ===

1) Choose your page family name. It should be unique, all lower case, with no spaces (use underscores to separate words). There is a naming convention between the page family name and its related DMS database objects. Things are much easier if that convention is followed. 

2) Directly go to the main config db editing page: Use the following URL, except use your config db name on the end of it ( ''http://dmsdev.pnl.gov/config_db/show_db/your_page_family_name.db''). If you are truly making a new page family, the main config db page will show all the possible tables, but none will exist yet. When you create the first table, the config db file will be created and will be put into the correct folder on the website. 

=== Create the General_Params table  ===

3) Create "general_params" table, go the edit page, and click the "Suggest Additions" link. This is enough for a full-up page family with list report, detail report, and entry page. If you follow the naming convention, the entries in the table should match the DMS database objects. If you haven’t yet made those objects, now would be a good time. Click the "Update" link to populate the table. 
* If the table does not update
** Make sure the permissions are correct on the new config_db by connecting to Prismwebdev2.pnl.gov then
<pre>
sudo su -
cd /storage/www/html/dms2/application/model_config
chmod 664 new_page.db
</pre>
* If the table still cannot update, then create the controller (see step 4 below)
* May also need to update permissions on the controller php file, at /storage/www/html/dms2/application/controllers

The "Suggest SQL to make DMS database objects" link on the main config db page will bring up a page containing SQL skeleton code for making the corresponding DMS database objects. This code will require editing before it is usable, but it can be a helpful starting point. If the database objects already exist, but do not follow the naming convention, you will need to edit the "general_params" table to match them.

=== Create the Controller Module  ===

4) In order for DMS2 to serve the new page family, you have to create a controller module. If the controller module does not already exist, there will be a link for making it at the bottom of the main config db page (link name is '''Make Controller'''). When you click the link, a dialog box will appear.  Type the page family name into this text box and click OK.  Example names to type:
* Sample Prep Request
* EMSL Proposal Helper
* Ontology

A suitable controller module will now be created in the "controllers" folder of the website. Assuming that the list report view in the DMS database exists, the page at ''http://dmsdev.pnl.gov/your_page_family_name/report'' should be served.
* The page title displayed on the list report and detail report is defined by "$this->my_title" in the controller file
* Edit the controller file to adjust the capitalization.
* Make sure the permissions are correct on the new config_db by connecting to Prismwebdev2.pnl.gov and checking the permissions at /storage/www/html/dms2/application/controllers

=== Define Hot links  ===

5) Now establish the hotlink between the list report and the detail report. Create the list_report_hotlinks table and go to its edit page. Click the "Suggest Additions" link. The initial "insert" statement (it is set off by dashed lines) is a pretty good start at such a hotlink. Get rid of all the other suggested SQL and run just that insert. You will need to make sure that the "name" field refers to the appropriate column in the list report. When you refresh the page family page, you should see the hotlink, and be able to follow it.

==== Special Characters for Links ====

Use the at sign, @, in a hot link to explicitly define where to place the target text. Two examples:
* Organism detail report links NEWT_ID to ontology detail report
** Uses target = "ontology/show/@NEWT1"
** For example, 
*** http://dms2.pnl.gov/organism/show/1171 uses NEWT_ID 4577 to link to 
*** http://dms2.pnl.gov/ontology/show/4577NEWT1
* Dataset detail report links the Predefines Triggered value to the analysis job report
** Uses target = "analysis_job/report/-/-/-/~@/-/-/-/Auto predefined"
** For example, 
*** http://dms2.pnl.gov/dataset/show/QC_Shew_12_02__F10_27Apr13_Tiger_12-12-06 uses QC_Shew_12_02__F10_27Apr13_Tiger_12-12-06 to link to
*** http://dms2.pnl.gov/analysis_job/report/-/-/-/~QC_Shew_12_02__F10_27Apr13_Tiger_12-12-06/-/-/-/Auto%20predefined

=== Enable the Entry Page  ===

6) In order to make the entry page work, you need to create and populate the "form_fields" and the "sproc_args" tables. Fortunately, you can use the SQL from the "Suggest Additions" link pretty much as-is, if you are following the naming conventions. The fiddly part is that the view in DMS database that feeds the entry page (the one defined by "entry_page_data_table" in the "general_params" table) must have column names that match the internal field names in the "form_fields" table. The other fiddly part is that field names need to agree between the "form_fields" and the "sproc_args" tables (the help link should actually be helpful if you don’t understand what I’m talking about). 

=== Add Primary Filters ===

7) The next steps would be to add primary filters to the list report, hotlinks to the list report and detail report, and choosers to the entry page. That stuff is pretty straightforward. It gets complicated when you start adding commands to detail report pages and list reports, but that is for another day, I think.
