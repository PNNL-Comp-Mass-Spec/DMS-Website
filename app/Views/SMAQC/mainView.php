<?php
/**
 * Code for the default view for SMAQC.
 *
 * @author Trevor Owen <trevor.owen@email.wsu.edu>
 * @author Aaron Cain
 */
?>
<div id="main-page">
    <div class="statusTableContainer" >
        <table class="statustable" >
            <tr>
                <th>Instrument</th>
            </tr>
        <?php foreach($instrumentlist as $row): ?>
            <tr>
                <td style="text-align: left;"><a href="<?= site_url(join('/', array("smaqc", "instrument", $row))) ?>"><?=$row?></a></td>
            </tr>
        <?php endforeach; ?>

        </table>
    </div>
</div>
