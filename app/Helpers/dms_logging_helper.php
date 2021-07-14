<?php

// --------------------------------------------------------------------
function sproc_log_in($sproc, $user, $parms, $override = false) {
    $CI =& get_instance();
    $inhibit_sproc_call = $CI->config->item('inhibit_sproc_call');
    $log_enabled = $CI->config->item('sproc_call_log_enabled');

    // we are done if sproc logging not enabled
    if (!($log_enabled || $override)) {
        return $inhibit_sproc_call;
    }

    // local override forces sproc call inhibit
    if ($override) {
        $inhibit_sproc_call = true;
    }

    // message about sproc call
    $ma = array();
    $ma[] = $sproc;
    $ma[] = $user;
    $ma[] = date('n/j/Y H:i:s');
    $ma[] = "----";
    foreach ($parms as $a => $v) {
        $ma[] = $a . '->' . $v;
    }
    $ma[] = "----";
    $ma[] = ($inhibit_sproc_call) ? "Call to stored procedure was inhibited" : "Stored procedure was called";
    echo implode("<br>", $ma);
    return $inhibit_sproc_call;
}

// --------------------------------------------------------------------
function sproc_log_out($sproc, $user, $retval, $message) {
    $CI =& get_instance();
    $inhibit_sproc_call = $CI->config->item('inhibit_sproc_call');
    $log_enabled = $CI->config->item('sproc_call_log_enabled');

    // we are done if sproc logging not enabled
    if (!$log_enabled) {
        return;
    }

    // message about sproc call
    $ma = array();
    $ma[] = $sproc;
    $ma[] = $user;
    $ma[] = date('n/j/Y H:i:s');
    $ma[] = "retval->" . $retval;
    $ma[] = "message->" . $message;
    echo "<br>";
    echo implode("<br>", $ma);
}
