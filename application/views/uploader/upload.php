<!DOCTYPE html>
<html>
<head>
<title>Spreadsheet Loader</title>

<?php $this->load->view('resource_links/base2css') ?>
<?php $this->load->view('resource_links/base2js') ?>

<script type='text/javascript'>
gamma.pageContext = {};
gamma.pageContext.site_url = '<?= site_url() ?>';

$(document).ready(function () {
    $('#ss_entity_list_container').load(gamma.pageContext.site_url+'upload/directory'); // gamma.loadContainer(url, {}, ss_entity_list_container)
});

// called by javascript that is returned by upload operation
// into iframe and which is run immediately
function report_upload_results(file_name, error) {
    if(error != '') {
        $('#uploaded_file_name').val('');
//      $('#upload_error').html($error);
    } else {
        $('#uploaded_file_name').val(file_name);
//      $('#upload_error').html('Upload was successful');
        extract();
        clearSpreadsheetDisplay();
    }
}
function updateContainer(action, containerId, id) {
    var url = gamma.pageContext.site_url + 'upload/' + action;
    var p = {};
    p.file_name = $('#uploaded_file_name').val();
    p.id = id;
    if(!p.file_name) {alert('No file name'); return; }
    gamma.loadContainer(url, p, containerId);
}
// extract data from uploaded spreadsheet and display on page
function extract() {
    $('#master_control_container').show();
    updateContainer('extract_data', 'ss_entity_list_container', '');
//  showSpreadsheetContents();
}
function showSpreadsheetContents() {
    $('#ss_table_display_area').show();
    updateContainer('extract_table', 'ss_table_container', '');
}
function clearSpreadsheetDisplay() {
    $('#ss_table_display_area').hide();
    $('#ss_table_container').html("")
}
</script>

</head>

<body>
<div id="body_container" >

<?php $this->load->view('nav_bar') ?>

<div class='local_title'><?= $title; ?></div>

<div style='padding:5px 0 0 0;' >

<?php
    // File parsing logic is in file application/controllers/Upload.php
    // which in turn calls load in   application/libraries/Spreadsheet_loader.php" -->
 ?>
<form action = "<?= site_url() ?>upload/load" method="post" enctype="multipart/form-data" target="upload_target" >
File to upload:
<input name="myfile" id="myfile" type="file" size="120"/>
<input type="submit" name="submitBtn" value="Upload" title="Upload local file to DMS" />
</form>
<font size="-2">Supported formats: .xlsx/.xls, .tsv (tab-delimited), .odf (Open/LibreOffice Calc), .csv</font>
</div>

<div style="padding:10px 0 5px 0;">
Uploaded file:
<input id='uploaded_file_name' type='text' size='40' />
<span id='upload_error' ></span>
<input class="button search_btn" type="button" onclick="extract()" value="Extract Data" title="Get list of entities from file and controls to load them" />
<input class="button search_btn" type="button" onclick="showSpreadsheetContents()" value="Display Contents" title="Display contents of file in tabular format" />
</div>

<table>
<tr>
<td style='vertical-align:top;' ><div style='height:10px;'></div><div id='master_control_container' style='display:none;border:2px solid #AAA;'><?php $this->load->view('uploader/upload_controls') ?></div></td>
<td style='vertical-align:top;' ><div id='ss_entity_list_container'><a href='javascript:void(0)' onclick="$('#ss_entity_list_container').load(gamma.pageContext.site_url+'upload/directory')">Help</a></div></td>
</tr>
</table>

<div id='ss_table_display_area' style='display:none;' >
<div style='padding-top:5px;' >
<a onclick='clearSpreadsheetDisplay()' href='javascript:void(0)' title="Clear spreadsheet display" >Clear Spreadsheet Display</a> &nbsp;
</div>
<div id='ss_table_container'></div>
</div>

<iframe id="upload_target" name="upload_target" src="#" style="display:none"></iframe>

</div>
</body>
</html>
