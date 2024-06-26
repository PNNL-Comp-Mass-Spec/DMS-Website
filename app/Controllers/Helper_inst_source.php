<?php
namespace App\Controllers;

class Helper_inst_source extends DmsBase {
    function __construct()
    {
        $this->my_tag = "helper_inst_source";
//      $this->my_model = "";
        $this->my_title = "Source Files for Datasets";
        $this->my_list_action = "";
        $this->my_export_action = "";
    }

    // --------------------------------------------------------------------
    // Present file contents with chooser links
    function view($inst = "")
    {
        helper(['url', 'text']);

        if (empty($inst)) {

            echo "<p>Commonly used DMS Instruments</p>\n";
            echo "<!-- To edit this list, see file DMS2/app/Controllers/helper_inst_source.php -->\n";

            echo "<ul>\n";
            $instruments = array();
            $instruments[] = "12T_FTICR_B";
            $instruments[] = "15T_FTICR";
            $instruments[] = "15T_FTICR_Imaging";
            $instruments[] = "21T_Agilent";
            $instruments[] = "Scimax01";
            $instruments[] = "Agilent_GC_MS_01";
            $instruments[] = "Agilent_GC_MS_02";
            $instruments[] = "Agilent_GC_MS_03";
            $instruments[] = "Agilent_QQQ_04";
            $instruments[] = "Altis01";
            $instruments[] = "Altis02";
            $instruments[] = "Altis03";
            $instruments[] = "GCQE01";
            $instruments[] = "IMS08_AgQTOF05";
            $instruments[] = "IMS09_AgQToF06";
            $instruments[] = "IMS12-AgQToF09";
            $instruments[] = "Lumos01";
            $instruments[] = "Lumos02";
            $instruments[] = "Lumos03";
            $instruments[] = "Eclipse01";
            $instruments[] = "Eclipse02";
            $instruments[] = "Ascend01";
            $instruments[] = "QExactHF03";
            $instruments[] = "QExactHF05";
            $instruments[] = "QExactP02";
            $instruments[] = "QExactP04";
            $instruments[] = "QExactP06";
            $instruments[] = "QEHFX01";
            $instruments[] = "QEHFX02";
            $instruments[] = "QEHFX03";
            $instruments[] = "Exploris02";
            $instruments[] = "SLIM02_AgQTOF02";
            $instruments[] = "SLIM03_AgTOF06";
            $instruments[] = "SLIM07_AgTOF08";
            $instruments[] = "TSQ_4";
            $instruments[] = "TSQ_5";
            $instruments[] = "TSQ_6";
            $instruments[] = "VOrbi05";
            $instruments[] = "VOrbiETD02";

            foreach ($instruments as $instrument)
            {
                echo "<li><a href=\"/helper_inst_source/view/" . $instrument . "\">" . $instrument. "</a></li>\n";
            }

            exit;
        }

        // Get source content file from website
        $cfg = config('App')->dms_inst_source_url;
        $url = $cfg ? $cfg : "http://gigasax.pnl.gov";
        $file = fopen ($url."/DMS_Inst_Source/".$inst."_source.txt", "r");
        if (!$file) {
            echo "<p>Unable to open source file.</p>\n";
            echo "<p>See the list of <a href=\"/helper_inst_source/view/\">commonly used DMS instruments</a></li>\n";
            echo "<!-- To edit the list, see file DMS2/app/Controllers/helper_inst_source.php -->\n";
            exit;
        }

        $data['title'] = $this->my_title;
        $data['heading'] = "Source Files for $inst";
        $data['subheading'] = "";

        $showDotDMessage = false;

        // Open instrument source file and read the data line-by-line
        // Use this to generate data that will be displayed as a table

        $headerRow = array();
        $files = array();
        $dirs = array();
        $other = array();

        $headerRow[] = "||File or Directory||Type||Size||DMS Detail Report";

        while (!feof ($file)) {
            $line = fgets ($file, 1024);

            // Skip blank lines
            if(preg_match("/^\s*$/", $line)) continue;

            if ($data['subheading'] == "" &&
                (strpos($line, "Folder:") === 0 || strpos($line, "Directory:") === 0)) {
                $data['subheading'] = $line;
                continue;
            }

            // Split on tabs
            $flds = preg_split('/[\t]/', $line);
            $type = trim($flds[0]);
            $value = trim($flds[1]);

            // If three fields are present, assume the 3rd field is file size
            if(count($flds) > 2)
                $size = trim($flds[2]);
            else
                $size = "";

            // Clean off file extensions
            $valueClean = preg_replace('/(\.raw$|\.wiff$|\.d$|\.uimf$)/i'  , '', $value );

            // Hide certain files
            if ($value === "Use_dir_slashAS_for_hidden_DotD_folders.txt" ||
                $value === "desktop.ini" ||
                preg_match("/upload.txt$/i", $value) ||
                preg_match("/\.(sld|meth|log|bat)$/i", $value)  ) {
                continue;
            }

            // Make name into link, as appropriate (file or dir)
            // Do not link items that start with x_ or that end with .sld
            // Also skip text file Use_dir_slashAS_for_hidden_DotD_folders.txt
            if (($type == 'File' || $type == 'Dir') &&
                !preg_match("/^x_/i", $valueClean) &&
                !preg_match("/\.(sld|meth|txt|log)$/i", $value)
               )
            {
                $lnk = "<a href='javascript:opener.dmsChooser.updateFieldValueFromChooser(\"$valueClean\", \"replace\")' >$value</a>";
            } else {
                $lnk = $value;
            }

            $datasetLink = "";
            if (strpos($valueClean, "x_") === 0) {
                $datasetLink = "<a href=\"/dataset/show/" . substr($valueClean, 2) . "\" target=_blank>Show</a>";
            } else if (preg_match("/\.raw$/i", $value) ||
                       preg_match("/\.d$/i", $value) ) {
                $datasetLink = "<a href=\"/dataset/show/" . $valueClean . "\" target=_blank>Show</a>";
            }

            // Put into proper category
            switch($type) {
                case 'File':
                    $fileInfo = "|$lnk|$type|";
                    if(strlen($size) > 0)
                        $fileInfo .= $size;
                    else
                        $fileInfo .= " ";

                    $files[] = $fileInfo . "|" . $datasetLink;
                    break;

                case 'Dir':
                    $dirInfo = "|$lnk|$type|";
                    if(strlen($size) > 0)
                        $dirInfo .= $size;
                    else
                        $dirInfo .= " ";

                    $dirs[] = $dirInfo . "|" . $datasetLink;

                    if (preg_match("/\.d$/i", $value)) {
                        $showDotDMessage = true;
                    }

                    break;

                default:
                    $other[] = "|$lnk|$type| |";
                    break;
            }
        }

        if ($showDotDMessage) {
            $dirs[] = "";
            $dirs[] = "Use dir /ah to see hidden .D folders";
        }

        fclose($file);

        $result = array_merge($headerRow, $other, $dirs, $files);
        $data['result'] = $result;

        // Load up data array and call view template
        echo view('tabular_data', $data);
    }
}
?>
