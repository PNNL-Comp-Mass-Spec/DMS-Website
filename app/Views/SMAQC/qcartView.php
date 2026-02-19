<?php
/**
 * Code for the qcartView.
 */

    function format_metric($metric)
    {
        if(is_null($metric))
            return "";

        if ($metric == 0)
            return "0";

        if (abs($metric) < 0.01)
            return number_format($metric, 4);

        if (abs($metric) < 0.1)
            return number_format($metric, 3);

        if (abs($metric) < 1)
            return number_format($metric, 2);

        if (abs($metric) < 10)
            return number_format($metric, 1);

        return number_format($metric, 0);
    }

    function link_to_instrument_dash($instrument, $filterDS = false, $ignoreDS = false)
    {

        $URI_elements = array('smaqc', 'instrument', $instrument);

        if($filterDS != false)
        {
            $URI_elements[] = "filterDS";
            $URI_elements[] = $filterDS;
        }

        if($ignoreDS != false)
        {
            $URI_elements[] = "ignoreDS";
            $URI_elements[] = $ignoreDS;
        }

        return site_url(join("/", $URI_elements));
    }

    function link_to_metric_dash($metricname, $instrument, $filterDS = false, $ignoreDS = false)
    {
        // Required URL parameters:
        // metric: the name of the metric
        // instrument: the name of the instrument

        // Optional URL parameters:
        // filterDS: used to select datasets based on a SQL 'LIKE' match
        // ignoreDS: used to exclude datasets based on a SQL 'LIKE' match

        $URI_elements = array('smaqc', 'qcart', 'inst', $instrument);

        if($filterDS != false)
        {
            $URI_elements[] = "filterDS";
            $URI_elements[] = $filterDS;
        }

        if($ignoreDS != false)
        {
            $URI_elements[] = "ignoreDS";
            $URI_elements[] = $ignoreDS;
        }

        return site_url(join("/", $URI_elements));
    }

?>
<div id="left-menu">
  <ul class="menuitems">
    <li><button class="button" onClick="location.href='<?= link_to_instrument_dash($instrument) ?>'">Home</button></li>
    <li>
        <strong>Settings</strong><br />

        Instrument:
        <select id="instrumentlist">
            <?php foreach($instrumentlist as $row): ?>
                  <?php if($instrument == $row) { ?>
                    <option value="<?=$row?>" selected="selected"><?=$row?></option>
                <?php } else { ?>
                    <option value="<?=$row?>"><?=$row?></option>
                <?php } ?>
              <?php endforeach; ?>
        </select>

        <label for="from">From</label>
        <input type="text" id="from" name="from" value="<?=$startdate?>" />

        <label for="to">To</label>
        <input type="text" id="to" name="to" value="<?=$enddate?>" />

        <label for="filterDS">Dataset Filter</label>
        <input type="text" id="filterDS" name="filterDS" value="<?=$filterDS?>" />

        <button id="updatesettings" class="button">Update</button>
    </li>
  </ul>
</div>

<div id="main-page">
<p><?=$definition?></p>

<div id="chartdiv" style="height:480px; width:100%; margin-bottom:40px;"></div>
  <table border=1 >
    <tr>
      <th>Dataset ID</th>
      <th>Start Time</th>
      <th>Value</th>
      <th>Rating</th>
      <th>Dataset</th>
    </tr>
<?php foreach($metrics->getResult() as $row): ?>
    <tr>
      <td align="center"><?=$row->dataset_id?></td>
      <td><?=preg_replace('/:[0-9][0-9][0-9]/', '', $row->acq_time_start)?></td>
      <td align="center"><?=format_metric($row->$metric)?></td>
      <td><?=$row->dataset_rating?></td>
      <td><a href="http://dms2.pnl.gov/dataset/show/<?=$row->dataset?>" target="_Dataset"><?=$row->dataset?></a></td>
    </tr>
<?php endforeach; ?>
  </table>
</div>
