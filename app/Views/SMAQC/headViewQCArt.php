<?php
/**
 * headViewQCArt.php
 *
 * File containing the opening code for view qcartView
 *
 */
?>
<!DOCTYPE html>
<html>
<head>
<title>SMAQC</title>
<meta charset="UTF-8">
<link rel="stylesheet" type="text/css" href="<?= base_url("css/SMAQC/layout.css") ?>" />
<link rel="stylesheet" type="text/css" href="<?= base_url("css/SMAQC/present.css") ?>" />
<link rel="stylesheet" type="text/css" href="<?= base_url("javascript/jquery-ui-1.14.1/jquery-ui.min.css") ?>" />
<script type="text/javascript" src="<?= base_url("javascript/jquery/jquery-3.7.1.min.js") ?>"></script>
<script type="text/javascript" src="<?= base_url("javascript/jquery-ui-1.14.1/jquery-ui.min.js") ?>"></script>
<script type="text/javascript" src="<?= base_url("javascript/SMAQC/calendar_pop.js") ?>"></script>

<?php if( $includegraph ) :?>
    <script type="text/javascript" src="<?= base_url("javascript/echarts/echarts.min.js") ?>"></script>
    <script type="text/javascript">
        <?php
            // we need to set these dates, as well as other values in the Settings var
            // metricPlot.js will use them

            // we need to get the date in milliseconds for jqplot
            /*
            $tempStartDate = explode('-', $startdate);
            $tempStartDate = $tempStartDate[2] . '-' . $tempStartDate[0] . '-' . $tempStartDate[1];
            $jqStartdate = new DateTime($tempStartDate);

            $tempEndDate = explode('-', $enddate);
            $tempEndDate = $tempEndDate[2] . '-' . $tempEndDate[0] . '-' . $tempEndDate[1] . ' 23:59:59';
            $jqEnddate = new DateTime($tempEndDate);
            */
        ?>
        var Settings = {
            title: '<?=$title?>',
            plotdata: <?=$plotdata?>,
            plotdata_average: <?=$plotdata_average?>,
            stddevupper: <?=$stddevupper?>,
            stddevlower: <?=$stddevlower?>,
            plotDataBad: <?=$plotDataBad?>,
            plotDataPoor: <?=$plotDataPoor?>,
            metric_units: <?=$metric_units?>
        };

        var filterText = '<?=$datasetfilter?>';

        $(window).on("resize", function() {
            plot.resize();
        });
    </script>
    <script type="text/javascript" src="<?= base_url("javascript/SMAQC/metricPlot.js") ?>"></script>

<?php endif; ?>

<script type="text/javascript">
  $(function() {

    $(".button").button();

    $(".categorytitle").on("click", function() {
        $(this).siblings('table').toggle();
    });

    $(".categorylink").on("click", function() {
        $(this.hash).children().show();
    });

    $("#updatesettings").on("click", function() {
        var newurl = '<?=site_url('smaqc/qcart')?>';

        newurl = newurl + '/inst/' + $("#instrumentlist").val();

        if($("#from").length)
        {
            newurl = newurl + '/from/' + $("#from").val();
        }

        if($("#to").length)
        {
            newurl = newurl + '/to/' + $("#to").val();
        }

        if($("#filterDS").length)
        {
            var txt = $("#filterDS").val();
            if(txt != "")
                newurl = newurl + '/filterDS/' + $("#filterDS").val();
        }

        if($("#ignoreDS").length)
        {
            var txt = $("#ignoreDS").val();
            if(txt != "")
                newurl = newurl + '/ignoreDS/' + $("#ignoreDS").val();
        }

        document.location.href = newurl;
    });
  });
</script>
</head>

<body>
