<?php $num_cols = count(current($rows)); ?>
<table class="LRep" >

<tr><?= $column_header ?></tr>

<?php foreach($rows as $row):?>
<tr class="<?= alternator('ReportEvenRow', 'ReportOddRow');?>" >
<?= $row_renderer->render_row($row); ?>
</tr>
<?php endforeach;?>

</table>
