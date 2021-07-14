<!DOCTYPE html>
<html>
<head>
<title><?= $title; ?></title>

<?php echo view('resource_links/base2css') ?>
<?php echo view('resource_links/base2js') ?>

</head>
<body id='freezer_page'>
<div id="body_container" >

<?php echo view('nav_bar') ?>

<div style="padding:2px 0 2px 0;">
<h2 class='page_title' style="display:inline;"><?= $heading; ?></h2>
</div>

<?php

    if (!$this->cu->check_access('operation', true)) {
        echo "<p>You do not have permission to update items on this page</p>";
    } else {
        // echo "<p>You DO have permission to update items on this page</p>";

        // show contents of locations in tables
        $tmpl = array (
            'table_open'  => '<table border="1" cellpadding="2" cellspacing="1" class="GridCell">',
            'heading_cell_start' => '<th class="block_header" colspan="4">'
        );
        $this->table->setTemplate($tmpl);
        //
        foreach($storage as $freezer => $f) {
            foreach($f as $shelf => $s) {
                foreach($s as $rack => $rk) {
                    $this->table->setHeading("Freezer:$freezer &nbsp; Shelf:$shelf &nbsp; Rack:$rack");
                    //
                    foreach($rk as $row => $rw) {
                        $tr = array();
                        foreach($rw as $col => $location) {
                            $x = render_location_contents($location, $contents);
                            if($x) $tr[] = $x;
                        }
                        $this->table->addRow($tr);
                    }
                    //
                    echo $this->table->generate();
                    $this->table->clear();
                    echo "<br>";
                }
            }
        }
    }
?>

</div>
</body>
</html>
