<!DOCTYPE html>
<html>
<head>
<title>Spreadsheet Loader</title>

<?php echo view('resource_links/base2css') ?>
<?php echo view('resource_links/base2js') ?>

<?php // Import dmsUpload.js ?>
<?php echo view('resource_links/dmsUpload') ?>

<?php // Import fileDragDrop.css ?>
<?php echo view('resource_links/fileDragDrop_css') ?>

<script type='text/javascript'>
dmsjs.pageContext = {};
dmsjs.pageContext.site_url = '<?= site_url() ?>';
dmsjs.pageContext.my_tag = '<?= $my_tag ?>';
dmsjs.pageContext.autoUpload = true;
dmsjs.pageContext.fileUploadMaxSizeMB = 1;

$(document).ready(function () {
    $('#ss_entity_list_container').load(dmsjs.pageContext.site_url+dmsjs.pageContext.my_tag+'/directory'); // dmsOps.loadContainer(url, {}, ss_entity_list_container)
});

</script>

</head>

<body>
<div id="body_container" >

<?php echo view('nav_bar') ?>

<div class='local_title'><?= $title; ?></div>

<div style='padding:5px 0 0 0;' >

<?php
    // File parsing logic is in file app/Controllers/Spreadsheet_loader.php
    // which in turn calls load in   app/Libraries/Spreadsheet_loader.php" -->
 ?>
<form action = "<?= site_url($my_tag.'/load') ?>" method="post" enctype="multipart/form-data" target="upload_target" class="box no-js">
<div class="box__input">
Click the text below to choose a file<span class="box__dragndrop"> or drag a file here</span>.<br><br>
<input name="myfile" id="myfile" type="file" size="120" class="box__file" placeholder="Browse or drop here"/>
<label for="myfile"><strong>Choose a file...</strong></label>
<input type="submit" name="submitBtn" value="Upload" class="box__button" title="Upload local file to DMS" />
</div>
<div class="box__warn">Warning! <span></span>.</div>
<div class="box__error">Error! <span></span>.</div>
</form>
<font size="-2">Supported formats: .xlsx/.xls, .tsv (tab-delimited), .odf (Open/LibreOffice Calc), .csv</font>

</div>

<div style="padding:10px 0 5px 0;">
Uploaded file:
<input id='uploaded_file_name' type='text' size='40' />
<span id='upload_error' ></span>
<input class="button search_btn" type="button" onclick="dmsUpload.extract()" value="Extract Data" title="Get list of entities from file and controls to load them" />
<input class="button search_btn" type="button" onclick="dmsUpload.showSpreadsheetContents()" value="Display Contents" title="Display contents of file in tabular format" />
</div>

<table>
<tr>
<td style='vertical-align:top;' ><div style='height:10px;'></div><div id='master_control_container' style='display:none;border:2px solid #AAA;'><?php echo view('uploader/upload_controls') ?></div></td>
<td style='vertical-align:top;' ><div id='ss_entity_list_container'><a href='javascript:void(0)' onclick="$('#ss_entity_list_container').load(dmsjs.pageContext.site_url+dmsjs.pageContext.my_tag+'/directory')">Help</a></div></td>
</tr>
</table>

<div id='ss_table_display_area' style='display:none;' >
<div style='padding-top:5px;' >
<a onclick='dmsUpload.clearSpreadsheetDisplay()' href='javascript:void(0)' title="Clear spreadsheet display" >Clear Spreadsheet Display</a> &nbsp;
</div>
<div id='ss_table_container'></div>
</div>

<iframe id="upload_target" name="upload_target" src="#" style="display:none"></iframe>

</div>

<?php // Import fileDragDrop.js ?>
<?php echo view('resource_links/fileDragDrop_js') ?>

</body>
</html>
