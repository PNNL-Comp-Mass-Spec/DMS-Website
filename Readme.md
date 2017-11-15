# DMS Website

## Overview

The PNNL Data Management System (DMS) is composed of a SQL Server backend and website front end to allow PNNL staff to track biomaterial, samples, instrument data, and result files from automated software analyses.  This repository tracks the files behind the DMS website.

## Components

The DMS website is powered by CodeIgniter, running on Apache.  Data is organized by Page Families, which define the source of data to display and the database objects (and optionally Javascript libraries) for adding/updating data.  For example, the page family includes:
* Dataset List Report Page
  * List of instrument datasets
* Dataset Detail Report Page
  * Details on a single instrument dataset
* Edit Dataset Page
  * Edit an existing dataset
* New Dateset Page (name overridden as Create Dataset Trigger File)
  * Enter a new dataset

The information that defines a page family is stored in SQLite data files. The DMS_DB_Sql folder contains text-dumps of the SQL tracked by the SQLite data files.  For example, `application/model_config/DMS_DB_Sql/dataset.sql` contains the settings for the Dataset page family.

## License

Licensed under the Apache License, Version 2.0; you may not use this file except in compliance with the License.
You may obtain a copy of the License at http://www.apache.org/licenses/LICENSE-2.0