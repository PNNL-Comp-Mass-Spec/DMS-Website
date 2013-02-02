
<div id='attachments_control_section'>
<?= general_visibility_control('File Attachments', 'file_attachments_section', '') ?>
</div>

<div id='file_attachments_section'> 

<div id='file_attachments_box'>

<div id="attachments_list" ></div>

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
<td colspan="2">
	<input class="button" type="button" name="submit2Btn" value="Upload" title="Upload local file to DMS" onClick="fileAttachment.do_upload()" />
	<span id="result_display" style='padding:5px;' ></span>
	</td>
</tr>
</table>
</form>

</div>
</div>
<div style="height:1em;"></div>

<iframe id="upload_target" name="upload_target" src="#" ></iframe>
