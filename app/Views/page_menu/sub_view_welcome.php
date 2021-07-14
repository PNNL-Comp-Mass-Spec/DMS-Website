<?php
    function xor_string($string, $key) {
        for($i = 0; $i < strlen($string); $i++)
            $string[$i] = ($string[$i] ^ $key[$i % strlen($key)]);
        return $string;
    }
?>

<div style='padding:6px;'>
<div style='position:relative; height:1em;'>
    <div class='qs_WelcomeHeaders' style='position:absolute; top:0; left:0em;'>
    <a href="javascript:showHideMenuDiagram();"><span id='diag_ctl_label'>Show Section Menus</span></a>
    </div>
    <div class='qs_WelcomeHeaders' style='position:absolute; top:0; left:12em;'>
    <a href='<?= config('app')->pwiki ?>Data_Management_System' target='#PrismWiki'>Overview...</a>
    </div>
    <div class='qs_WelcomeHeaders' style='position:absolute; top:0; left:20em;'>
    <a href='<?= config('app')->pwiki ?>DMS_Getting_Started' target='#PrismWiki'>Getting Started...</a>
    </div>
    <div class='qs_WelcomeHeaders' style='position:absolute; top:0; left:30em;'>
    <a href='<?= config('app')->pwiki ?>PRISM_QuickStart_Guide' target='#PrismWiki'>DMS Quick Start...</a>
    </div>
    <div class='qs_WelcomeHeaders' style='position:absolute; top:0; left:40em;'>
    <a href='http://dmsbeta.pnl.gov' target='#PrismWiki'>Training site...</a>
    </div>
    <div class='qs_WelcomeHeaders' style='position:absolute; top:0; left:49em;'>
    <a href='https://jira.pnnl.gov/jira/secure/CreateIssue.jspa?pid=10900&issuetype=7&Create=Create' target='#Jira'>Proteomics queue...</a>
    </div>
</div>
</div>

<MAP NAME="visImageMap">
  <AREA shape="POLYGON" onmouseover="showFlyMenuOnDelay('Biomaterial')" onmouseout="cancelShowFlyMenuOnDelay()" ALT="Cell Culture/Biomaterial" TITLE="Cell Culture/Biomaterial" HREF="<?= site_url('cell_culture/report') ?>" COORDS="243,242,409,242,409,170,243,170,243,242">
  <AREA shape="POLYGON" onmouseover="showFlyMenuOnDelay('Data Analysis')" onmouseout="cancelShowFlyMenuOnDelay()" ALT="Data Analysis Job" TITLE="Data Analysis Job" HREF="<?= site_url('analysis_job/report') ?>" COORDS="243,600,409,600,409,528,243,528,243,600" >
  <AREA shape="POLYGON" onmouseover="showFlyMenuOnDelay('Datasets')" onmouseout="cancelShowFlyMenuOnDelay()" ALT="Dataset  (LCMS Run)" TITLE="Dataset  (LCMS Run)" HREF="<?= site_url('dataset/report') ?>" COORDS="243,480,409,480,409,409,243,409,243,480" >
  <AREA shape="POLYGON" onmouseover="showFlyMenuOnDelay('Experiments')" onmouseout="cancelShowFlyMenuOnDelay()" ALT="Experiment (Prepared Sample)" TITLE="Experiment (Prepared Sample)" HREF="<?= site_url('experiment/report') ?>" COORDS="243,361,409,361,409,289,243,289,243,361" >
  <AREA shape="POLYGON" onmouseover="showFlyMenuOnDelay('Analysis Request')" onmouseout="cancelShowFlyMenuOnDelay()" ALT="Anaysis Job Request" TITLE="Anaysis Job Request" HREF="<?= site_url('analysis_job_request/report') ?>" COORDS="1,600,168,600,168,528,1,528,1,600" >
  <AREA shape="POLYGON" onmouseover="showFlyMenuOnDelay('Requested Run')" onmouseout="cancelShowFlyMenuOnDelay()" ALT="Requested Run" TITLE="Requested Run" HREF="<?= site_url('requested_run/report') ?>" COORDS="1,480,168,480,168,409,1,409,1,480" >
  <AREA shape="POLYGON" onmouseover="showFlyMenuOnDelay('Material Storage')" onmouseout="cancelShowFlyMenuOnDelay()" ALT="Material Storage (Freezers)" TITLE="Material Storage (Freezers)" HREF="<?= site_url('material_container/report/-/-/-') ?>" COORDS="1,242,168,242,168,170,1,170,1,242" >
  <AREA shape="POLYGON" onmouseover="showFlyMenuOnDelay('Sample Prep')" onmouseout="cancelShowFlyMenuOnDelay()" ALT="Sample  Prep  Request" TITLE="Sample  Prep  Request" HREF="<?= site_url('sample_prep_request/report') ?>" COORDS="1,361,168,361,168,289,1,289,1,361" >
  <AREA shape="POLYGON" onmouseover="showFlyMenuOnDelay('Campaign')" onmouseout="cancelShowFlyMenuOnDelay()" ALT="Campaign" TITLE="Campaign" HREF="<?= site_url('campaign/report') ?>" COORDS="243,88,409,88,409,16,243,16,243,88" >
</MAP>

<div id='diagram_section' style='position:relative;margin-left:15px;'>
    <div style='height:15px;'></div>
    <div>
    <img src='<?= base_url("images/dms_hierarchy_5.gif") ?>' border='0' USEMAP="#visImageMap">
    </div>

    <div id='fly_section' style='position:absolute;top:2em;left:700px;width:25em;'>
    <?= make_fly_section_layout($qs_section_defs); ?>
        <div id='splash_message' class='fly_box'>
        <h2>DMS Hierarchy </h2>
        <p>DMS uses these entities to keep track of sample and data processing.</p>
        <p>An understanding of what each one represents, and how it fits in, is essential for proper use of DMS.</p>
        <p>If you haven't a clue where to start, try <a href='<?= config('app')->pwiki ?>Data_Management_System' target='#PrismWiki'>Overview...</a></p>
        <p>If want to dig into the basics, try <a href='<?= config('app')->pwiki ?>DMS_Getting_Started' target='#PrismWiki'>Getting Started...</a></p>
        <p>If you want to learn more about the features that DMS provides, try <a href='<?= config('app')->pwiki ?>PRISM_QuickStart_Guide' target='#PrismWiki'>DMS Quick start...</a></p>
        <p>See the <a href='<?= site_url("gen/stats")?>'>statistics...</a> page for bulk stats by entity type.</p>
        </div> <!-- end 'splash_message' -->
    </div> <!-- end 'fly_section' -->
</div> <!-- end 'diagram_section' -->

<div style='height:10px;'></div>
<div id='menu_sections' style='display:none;'>
<?= make_qs_layout($qs_section_defs); ?>
</div> <!-- end 'menu_sections' -->

<div style='height:2em;'></div>
<div id='disclaimer_message' style="width:55em;">
<p>
The DMS is part of PRISM, the Pan-omics Research Information Storage and Management System.
DMS acquires data from mass spectrometers and other instruments, collects laboratory information, and tracks and controls the intermediate data processing.
</p>
<p>
You may use the <a href='https://jira.pnnl.gov/jira/secure/CreateIssue.jspa?pid=10900&issuetype=7&Create=Create' target='#Jira'>Proteomics Support Queue</a> (JIRA)
to request assistance (login with your PNNL username and password). In case of an urgent need for assistance, please contact one of the following:
</p>
<ul>
    <li>Ron Moore: <?php echo xor_string("CE^YS\^P","proteomics"); ?> (office) or                       <!-- xyz-6339 -->
                   <?php echo xor_string("EBVYP]\DWFBJ","proteomics"); ?> (cell)</li>                   <!-- xyz-4528 -->
    <li>Matt Monroe: e-mail during business hours or <?php echo xor_string("EBVYVXXDUCED","proteomics"); ?> after hours</li>   <!-- 375-wxyz -->
</ul>
<p>
PRISM was created by Pacific Northwest National Laboratory for the U.S. Department of Energy under Contract Number DE-AC06-76RLO1830 and is operated under Contract Number DE-AC05-76RL01830.
</p>
<br>
</div> <!-- end 'disclaimer_message' -->
