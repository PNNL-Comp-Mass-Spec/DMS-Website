<!DOCTYPE html>
<html>
<head>
<title><?= $title; ?></title>
<?php $this->load->view('resource_links/base2css') ?>
</head>

<body>
<div id="body_container" >

<?php $this->load->view('nav_bar') ?>

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
if($entry_submission_cmds != "") $this->load->view("submission_cmd/$entry_submission_cmds");
?>

<div id='end_of_content' style="height:1em;" ></div>
</div>

<?php $this->load->view('resource_links/base2js') ?>

<script type='text/javascript'>
    gamma.pageContext.site_url = '<?= site_url() ?>';
    gamma.pageContext.base_url = '<?= base_url() ?>';
    gamma.pageContext.my_tag = '<?= $this->my_tag ?>';
    epsilon.pageContext.containerId = 'form_container';
    epsilon.pageContext.modeFieldId = 'entry_cmd_mode';
    epsilon.pageContext.entryFormId = 'entry_form';
    epsilon.adjustEnabledFields();
</script>

<?php if($entry_submission_cmds != ""): ?>
<script src="<?= base_url().'javascript/entry.js?version=102' ?>"></script>
<script type='text/javascript'>gamma.pageContext.cmdInit = entry.<?= $this->my_tag ?>.cmdInit;</script>
<?php endif; ?>

<script type='text/javascript'>
    $(document).ready(function () {
        $('.sel_chooser').chosen({search_contains: true});
        gamma.autocompleteChooser.setup();
    });
    epsilon.actions.after = function() {
        $('.sel_chooser').chosen({search_contains: true});
    };
    if(gamma.pageContext.cmdInit) gamma.pageContext.cmdInit();
</script>

</body>
</html>
