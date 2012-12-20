<script type='text/javascript'>
function report_upload_results(msg) {
	$('#result_display').html(msg);
	showAttachments();
}

$(document).ready(function () { 
//	showAttachments();
	$('#entity_type').val("<?= $tag ?>");
	$('#entity_id').val("<?= $id ?>");
	}
)
function showAttachments() {
	var url =  "<?= site_url() ?>file_attachment/show_attachments";
	var p = {};
	p.entity_type = "<?= $tag ?>";
	p.entity_id = "<?= $id ?>";
	$.post(url, p, function (data) {
			$('#attachments_list').html(data);
			$('#file_attachments_section').show();
		}
	);
	$.post(url, p, function (data) {
			$('#attachments_list').html(data);
			$('#file_attachments_section').show();
		}
	);
}
function doOperation(faid, mode) {
	if(mode = 'delete') {
		if(!confirm('Are you sure you want to delete this attached file? This operation cannot be undone.')) return;
	}
	var url =  "<?= site_url() ?>file_attachment/perform_operation";
	var p = {};
	p.id = faid;
	p.mode = mode;
	$.post(url, p, function (data) {
			if(data != '') {
				alert(data);
			} else {
				showAttachments();
			}
		}
	);
}
function do_upload() {
	$('#result_display').html(globalAJAX.progress_message);
	$('#upload_form').submit();
}
</script>
<div id='attachments_control_section' style='padding:5px 0px 5px 5px;'>
<a title="Show or hide the file attachments section" href="#" onclick="sectionToggle('file_attachments_section', 0.5 );showAttachments()");>File Attachments...</a>
</div>

<div id='file_attachments_section' style='display:none;'> 

<div style='width:60em;margin:5px 0 0 0;padding:0px 5px 5px 5px;border:2px solid #AAA;' >

<div id="attachments_list" style='padding:0px 5px 5px 5px;' ></div>

<div id="result_display" style='padding:5px;' ></div>

<form id="upload_form" action="<?= site_url() ?>file_attachment/upload" method="post" enctype="multipart/form-data" target="upload_target" >

<input type="hidden" name="entity_type" id="entity_type" ></input>
<input type="hidden" name="entity_id" id="entity_id" ></input>

<table>
<tr>
<td>File to upload: </td>
<td><input type="file" name="userfile" id="userfile" size="80"/></td>
</tr>
<tr>
<td>Description:</td>
<td><input type="text" name="description" id="description" size="80" ></input></td>
</tr>
<tr>
<td><input type="button" name="submit2Btn" value="Upload" title="Upload local file to DMS" onClick="do_upload()" /></td>
</tr>
</table>
</form>

</div>
</div>
<div style="height:1em;"></div>

<iframe id="upload_target" name="upload_target" src="#" style="display:none;"></iframe>
