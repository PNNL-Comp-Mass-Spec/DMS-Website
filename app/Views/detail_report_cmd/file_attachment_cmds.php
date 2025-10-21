
<div id='attachments_control_section'>
<?php // Include a span with a default value in the section title, to be populated via javascript ?>
<?= general_visibility_control('File Attachments (<span id="attachments_count">0</span>)', 'file_attachments_section', '') ?>
</div>

<div id='file_attachments_section'>

<div id='file_attachments_box'>

<div id="attachments_list" ></div>

<?php // Import dms.js and jQuery to avoid issues with other script inclusions after this ?>
<?php echo view('resource_links/base2js') ?>
<?php // Import fileDragDrop.css ?>
<?php echo view('resource_links/fileDragDrop_css') ?>

<script type='text/javascript'>
dmsjs.pageContext.autoUpload = false;
dmsjs.pageContext.fileUploadMaxSizeMB = 200;
</script>

<form id="upload_form" action="<?= site_url('file_attachment/upload') ?>" method="post" enctype="multipart/form-data" target="upload_target" class="box no-js">

<input type="hidden" name="entity_type" id="entity_type" ></input>
<input type="hidden" name="entity_id" id="entity_id" ></input>

<div class="box__input">
Click the text below to choose a file<span class="box__dragndrop"> or drag a file here</span>.<br><br>
<input type="file" name="userfile" id="userfile" size="80" class="box__file" placeholder="Browse or drop here"/>
<label for="userfile"><strong>Choose a file...</strong></label>
<br ><br >Description:<br >
<input type="text" name="description" id="description" size="80" ></input>
<br>
<input class="box__button" type="submit" name="submit2Btn" value="Upload" title="Upload local file to DMS" onClick="fileAttachment.do_upload()" />
<span id="result_display" style='padding:5px;' ></span>
</div>
<div class="box__warn">Warning! <span></span>.</div>
<div class="box__error">Error! <span></span>.</div>
</form>

</div>
</div>
<div style="height:1em;"></div>
<form id="download_form" action="" method="post" target="upload_target"></form>
<iframe id="upload_target" name="upload_target" src="#" ></iframe>

<?php // Import fileDragDrop.js ?>
<?php echo view('resource_links/fileDragDrop_js') ?>
