<?php  
    if (!defined('BASEPATH')) {
        exit('No direct script access allowed');
    }
/**
 *
 */
class Aux_info_support {
    
    var $item_entry_url = "";
    var $item_entry_chooser_url = "";
    var $update_info_url = "";
    var $copy_info_url = "";
    var $show_url = "";
//  var $entity_id = "";
    
    // --------------------------------------------------------------------
    function __construct()
    {
    }

    // --------------------------------------------------------------------
    function get_update_response_container() {
        return $this->update_response_container;
    }
    
    // -----------------------------------
    function make_category_subcategory_selector($aux_info_def)
    {
        $str = '';
        $count = 0;
        foreach($aux_info_def as $cat => $cSpec) {
            $str .= "<optgroup label='$cat'>\n";
            $count++;
            foreach(array_keys($cSpec) as $sub) {
                $str .= "<option value='$cat|$sub'>$sub</option>\n";
                $count++;
            }
            $str .= "</optgroup>\n";
        }

        //loadItemEntryForm(url, selector_id, id_id)
        $cat_sub_sel_size = "15";
        $url = $this->item_entry_url;
        $size = ($count < $cat_sub_sel_size)?$count:$cat_sub_sel_size;
        $js = "onChange='loadItemEntryForm(\"$url\")'";
        $str = "<select id='Category_Subcategory' size='$size' $js >\n" . $str . "</select>\n";
        return $str;
    }
    
    /**
     * Create the entry form (and enclosing table)
     * for editing aux info items ()for one subcategory)
     * @param type $items
     * @param type $choices
     * @return string
     */
    function make_item_entry_form($items, $choices)
    {
        $in = 'FieldNamesEx[]';
        $iv = 'FieldValuesEx[]';
        $iv_id = 'FieldValuesEx';

        $str = "";
        $str .= "<table class='EPag'>";
        $str .= "<tr><th colspan=3>Edit Subcategory</th></tr>";
        foreach($items as $row) {
            // start table row
            $str .= "<tr>";
            // column for item name 
            // display name
            $str .= "<td>";
            $str .= "<span>".$row['Item']."</span>";
            // hidden input field to make sure name is POSTed
            $str .= "<span><input type='hidden' name='".$in."' value='".$row['Item']."' ></span>";
            $str .= "</td>";
            // column for item data entry field (and current value of item)
            if((int)$row['DataSize'] > 128) {
                $str .= "<td><textarea class='aiif' name='$iv' id='".$iv_id."_".$row['Item_ID']."' rows='2' cols='60' >".$row['Value']."</textarea></td>";  
            } else {
                $str .= "<td><input class='aiif' name='$iv' id='".$iv_id."_".$row['Item_ID']."'type='text' value='".$row['Value']."' size='64' maxlength='128'></td>";                  
            }
            // column for any choosers for allowed values for item
            if($row['HelperAppend'] != 'N') {
                $ccid = "allowed_value_chooser_container_".$row['Item_ID'];
                $str .= "<td><span id='".$ccid."'>";
                $str .= $this->make_allowed_values_chooser($choices, $row['Item'], $row['Item_ID'], $row['HelperAppend']);
                $str .= "</span></td>"; 
            } else {
                $str .= "<td></td>";                    
            }
            // close table row
            $str .= "</tr>";
        }
        $str .= "</table>";
        return $str;
    }

    // -----------------------------------
    function make_allowed_values_chooser($choices, $item, $item_id, $helper_append)
    {
        // extract only choices for the given item
        $options[''] = '';
        foreach($choices as $ch) {
            if($ch['Item']==$item) {
                $av = $ch['AllowedValue'];
                $options[$av] = $av;    
            }
        }
        //
        $chooser_id = "allowed_values_$item_id";
        $item_value_field = "FieldValuesEx_".$item_id;
        $mode = ($helper_append == 'A')?"append_comma":"replace";
        //
        $js = "";
        $js .= "id='$chooser_id' ";
        $js .= " onChange='epsilon.setFieldValueFromSelection(\"$item_value_field\", \"$chooser_id\", \"$mode\")'";
        return form_dropdown("$chooser_id", $options, '', $js);     
    }

    /**
     * Create HTML declaring a global variable as a javascript object
     * that contains information necessary to make AJAX calls
     */
    function make_aux_info_global_AJAX_definitions()
    {
        $throb = base_url()."images/throbber.gif";
        $str = '';  
        $str .= <<<EOD
<script type="text/javascript">
    var gAuxInfoAJAX = {
        progress_message:'<span class="LRepProgress">Working...<img src="$throb" /></span>'
        };
</script>

EOD;
        return $str;
    }

}
