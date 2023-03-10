<!DOCTYPE html>
<html>
<head>
<title><?= $title; ?></title>

<?php echo view('resource_links/base2css') ?>

</head>

<body>
<div id="body_container" >

<?php echo view('nav_bar') ?>

<div class='local_title'><?= $title; ?></div>

<div id='data_container'>
(data will be loaded here)
</div>

<?php if(!empty($commands)):?>
    <div id='update_message' class="RepCmdsResponse" ></div>
    <div class='DrepCmds'>
    <?= make_detail_report_commands($commands, $tag, $id) ?>
    </div>
    <div style="height:1em;" ></div>
<?php endif; ?>

<?php if(count($detail_report_cmds) > 0):?>
    <div id='command_box_container'>
    <?php foreach($detail_report_cmds as $cmd): ?>
    <?php echo view("detail_report_cmd/$cmd"); ?>
    <?php endforeach ?>
    </div>
<?php endif; ?>

<?php if($aux_info_target):?>
    <div id= 'aux_info_controls_container' class='DrepAuxInfo'></div>
    <div id= 'aux_info_container'></div>
    <div style="height:1em;" ></div>
<?php endif; ?>

<?php // export command panel
echo view("main/detail_report_export");
?>

<div id='end_of_content' style="height:1em;" ></div>
</div>

<?php echo view('resource_links/base2js') ?>

<script type='text/javascript'>
    //
    // dmsjs is defined in dms.js
    // detRep is defined in detRep.js
    //
    dmsjs.pageContext.site_url = '<?= site_url() ?>';
    dmsjs.pageContext.my_tag = '<?= $my_tag ?>';
    dmsjs.pageContext.responseContainerId = 'update_message';
    dmsjs.pageContext.Id = '<?= $id ?>';
    dmsjs.pageContext.aux_info_target = '<?= ($aux_info_target)?$aux_info_target:''; ?>';
    dmsjs.pageContext.ops_url = '<?= $ops_url ?>';
    dmsjs.pageContext.updateShowSQL = detRep.updateShowSQL;
    dmsjs.pageContext.updateShowURL = detRep.updateShowURL;
</script>

<script src="<?= base_url('javascript/file_attachment.js?version=100') ?>"></script>

<?php // When updating the version for aux_info.js, update both detail_report.php and aux_info_entry.php ?>
<script src="<?= base_url('javascript/aux_info.js?version=100') ?>"></script>

<script type='text/javascript'>
    function updateAuxIntoControls() {
        detRep.updateContainer(dmsjs.pageContext.my_tag + '/detail_report_aux_info_controls/' + dmsjs.pageContext.Id, 'aux_info_controls_container');
    }
    $(document).ready(function () {
        detRep.updateMyData();
        if(dmsjs.pageContext.aux_info_target) updateAuxIntoControls();
        fileAttachment.init();
    });
</script>

</body>
</html>
