<!DOCTYPE html>
<html>
<head>
<title><?= $title; ?></title>

<?php echo view('resource_links/base2css') ?>
<?php echo view('resource_links/base2js') ?>

</head>
<body>

<div style="padding:2px 0 2px 0;">
<h2 class='page_title' style="display:inline;"><?= $heading; ?></h2><br>
<h3 class='page_title' style="display:inline;"><?= $subheading; ?></h3>
</div>

<div id='data_display_container'>
<?php // data display section
// $result should be an array of strings
// Values between columns should be separated by vertical bars
// If the first row in $result starts with two vertical bars it is treated as a header row
//
// Example rows for $result
//  ||Filename||Type||Size||
//  |archive||Dir||46 GB||
//  |QC_Shew_16_01_pt5_run1.raw|File|324 MB|
//  |QC_Shew_16_01_pt5_run3.raw|File|378 MB|
//
if (is_string($result)) {
    echo $result;
} else
if(is_array($result)){
    echo "<table style='border: 1px solid black; padding: 1px; margin: 15px; border-collapse: collapse'>\n";
    $headerProcessed = false;
    foreach($result as $item) {
        $colDelimiter = "td";

        if ($headerProcessed === false) {
            if (strpos($item, "||") >= 0) {
                $colDelimiter = "th";
                $item = str_replace("||", "|", $item);
            }
            $headerProcessed = true;
        }

        echo "<tr>";

        // Split $item on vertical bars
        $columns = explode("|", $item);
        $colNumber = 0;
        foreach($columns as $columnValue) {
            $colNumber++;

            // The first column should be empty; skip it if it is
            if ($colNumber == 1 && strlen($columnValue) == 0)
                continue;

            echo "<$colDelimiter style='border: 1px solid #ABBB99; padding: 2px;'>$columnValue</$colDelimiter>";
        }

        echo "</tr>\n";
    }
    echo "</table>\n";
} else {
    echo "No results available";
}
?>
</div>

</body>
</html>
