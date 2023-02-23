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
<script src="<?= base_url('javascript/entry.js?version=106') ?>"></script>

<script type='text/javascript'>
    gamma.pageContext.site_url = '<?= site_url() ?>';
    gamma.pageContext.base_url = '<?= base_url() ?>';
    gamma.pageContext.my_tag = '<?= $my_tag ?>';
    gamma.pageContext.page_type = '<?= $page_type ?>';
    gamma.pageContext.url_segments = '<?= $url_segments ?>';
    gamma.pageContext.updateShowURL = entry.updateShowURL;
    epsilon.pageContext.containerId = 'form_container';
    epsilon.pageContext.modeFieldId = 'entry_cmd_mode';
    epsilon.pageContext.entryFormId = 'entry_form';
    epsilon.adjustEnabledFields();
</script>

<?php if($entry_submission_cmds != ""): ?>
    <script src="<?= base_url('javascript/entryCmds.js?version=100') ?>"></script>
    <script type='text/javascript'>gamma.pageContext.cmdInit = entry.<?= $my_tag ?>.cmdInit;</script>
<?php endif; ?>

<script type='text/javascript'>
    $(document).ready(function () {
        $('.sel_chooser').select2();
        gamma.autocompleteChooser.setup();
    });
    epsilon.actions.after = function() {
        $('.sel_chooser').select2();
    };
    if(gamma.pageContext.cmdInit) gamma.pageContext.cmdInit();
</script>

</body>
</html>
