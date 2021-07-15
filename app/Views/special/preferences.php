<!DOCTYPE html>
<html>
<head>
<title><?= $title; ?></title>

<?php echo view('resource_links/base2css') ?>
<?php echo view('resource_links/base2js') ?>

<script type="text/javascript">
    function setPreference(url) {
        var reply = prompt("Please enter a new value", "");
        if (reply == null || reply == "") {
            return;
        }
        // Replace non-breaking dashes with normal dashes
        var findNonBreakingSpace = new RegExp(String.fromCharCode(8209), "g");
        location = url + reply.replace(findNonBreakingSpace, "-");
    }
</script>

</head>
<body>
<div id="body_container" >
<?php echo view('nav_bar') ?>

<div style="padding:2px 0 2px 0;">
<h2 class='page_title' style="display:inline;"><?= $heading; ?></h2>
</div>

<div id='posting_message_container' style='width:70em;'>
<?php
if($result != "") {
    echo "<div class='EPag_error' >";
    echo $result;
    echo "</div>";
}
?>
</div>

<table style='border-spacing: 15px 8px;'>
<tr>
    <th style='text-align:left;'>Name</th>
    <th style='text-align:left;'>Value</th>
    <th></th>
    <th style='text-align:left;'>Description</th>
    <th style='text-align:left;'>Allowed Values</th>
</tr>
<?php
foreach($settings as $setting => $def) {
    $str = "";
    $str .= "<tr>";
    $str .= "<td><span style='font-weight:bold;'>".$def['label'] ."</span></td>";
    $str .= "<td>".$def['value']."</td>";
    $url = site_url("preferences/set/$setting/");
    $str .= "<td><a href='javascript:setPreference(\"$url\")'>Change</a></td>";
    $str .= "<td>".$def['description']."</td>";
    $str .= "<td>";

    if ($def['allowed_values'] === '-') {
        // Allowed values specifies a range of integers
        // Display the minimum then a dash then the maximum
        $str .= "<td>".implode($def['validation'], $def['allowed_values'])."</td>";
    }
    else {
        // Allowed values specifies a series of allowed strings
        // Display them as a comma-separated list
        // When constructing the list, prevent wrapping date format codes
        // Option 1: replace dashes with a non-breaking hyphen (code &#8209;) and spaces with a non-breaking space (&nbsp;)
        // Option 2: Use a span
        $allowedValueCount = count($def['allowed_values']);
        for ($i = 0; $i < $allowedValueCount; $i++) {
            $str .= '<span style="white-space: nowrap;">' . $def['allowed_values'][$i] . '</span>';
            if ($i < $allowedValueCount - 1) {
                // Add the value separator (will be a comma or dash)
                $str .= $def['validation'];
            }
        }
    }

    $str .= "</td>";
    $str .= "</tr>";
    echo $str;
}
?>
</table>


<a href='<?= base_url("preferences/session")?>'>Session</a>
</div>
</body>
</html>
