<div class='LRCmds'>
<h3>Commands</h3>

<form name="DBG" id="cmds" >
<ul>
<div>
Enter Dataset List:
</div>
<div>
<textarea name="Dataset_list" cols="70" rows="4" id="Dataset_list" maxlength="80" size="20" onChange="epsilon.convertList('Dataset_list', ',')" ></textarea>
</div>
<a href='javascript:lcmd.dataset_ext_cmds.transferData("dataset", "Dataset_list")'><img src='<?= base_url() ?>images/btn.png' border='0' ></a> Transfer Selected Datasets
</ul>
</form>
</div>
