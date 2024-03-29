<!DOCTYPE html>
<html>
<head>
<title><?= $title; ?></title>
<?php echo view('resource_links/base2css') ?>
</head>

<body>
<div id="body_container" >

<?php echo view('nav_bar') ?>

<h2 class='page_title'><?= $title; ?></h2>

<form name="frmEntry" id="entry_form" action="#">
<div id='form_container'>
<?= $form ?>
</div>
</form>

<div class='EPagCmds' style='clear:both;' id='cmd_buttons'>
<?= $entry_cmds ?>
</div>

<?php // any submission commands?
if($entry_submission_cmds != "") echo view("submission_cmd/$entry_submission_cmds");
?>

<div id='end_of_content' style="height:1em;" ></div>
</div>

<?php echo view('resource_links/base2js') ?>
<?php // Import entry.js ?>
<?php echo view('resource_links/entry') ?>

<script type='text/javascript'>
    dmsjs.pageContext.site_url = '<?= site_url() ?>';
    dmsjs.pageContext.base_url = '<?= base_url() ?>';
    dmsjs.pageContext.my_tag = '<?= $my_tag ?>';
    dmsjs.pageContext.page_type = '<?= $page_type ?>';
    dmsjs.pageContext.url_segments = '<?= $url_segments ?>';
    dmsjs.pageContext.updateShowURL = entry.updateShowURL;
    entry.pageContext.containerId = 'form_container';
    entry.pageContext.modeFieldId = 'entry_cmd_mode';
    entry.pageContext.entryFormId = 'entry_form';
    entry.adjustEnabledFields();
</script>

<?php if($entry_submission_cmds != ""): ?>
    <?php // Import entryCmds.js ?>
    <?php echo view('resource_links/entryCmds') ?>
    <script type='text/javascript'>dmsjs.pageContext.cmdInit = entryCmds.<?= $my_tag ?>.cmdInit;</script>
<?php endif; ?>

<script type='text/javascript'>
    $(document).ready(function () {
        $('.sel_chooser').select2();
        dmsChooser.autocompleteChooser.setup();
    });
    entry.actions.after = function() {
        $('.sel_chooser').select2();
    };
    if(dmsjs.pageContext.cmdInit) dmsjs.pageContext.cmdInit();
</script>

</body>
</html>
