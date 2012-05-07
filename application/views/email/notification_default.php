<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Transitional//EN\" \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd\">
<html xmlns=\"http://www.w3.org/1999/xhtml\" lang=\"en\">
<head>
<style type="text/css">
body {
    font-family: verdana, arial, helvetica, sans-serif;
    font-size: 11px;
}
table {
	font-size:11px;
	font-family:verdana, arial, helvetica, sans-serif;
	background-color:#B0B0B0;	
	margin-top:10px;
	border-spacing:1px;
}

table td {
	background-color:#FDF8E9;
	padding:5px;
}
table th {
	text-align:left;
	color:white;	
	padding:5px;
	background-color:#3366FF;	
}
</style>
</head>
<body>

<h2>DMS Event Notification</h2>

<p>Here are the DMS events of the last 24 hours for items that are associated with one or more campaign research teams that you are a member of.</p>

<?= $items ?>

<p>You are receiving this automatic email from DMS because you registered to do so.  You may change your registration by going to <a href='<?= site_url().'notification/edit/'. $prn ?>'>this page</a> </p>

</body>
</html>