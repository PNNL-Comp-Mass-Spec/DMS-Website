<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class URL_updater {
    
    private $protocol = "http";
    private $server_bionet = false;
    
    /**
     * Constructor
     */
    function __construct() {
        $this->protocol = isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] == "on" ? "https" : "http";
        // TODO: May be better to check for the 'pnl.gov' string in the name?
        $this->server_bionet = stripos($_SERVER["SERVER_NAME"], "bionet") !== false;
    }
    
    function fix_link($link) {
        if (stripos($link, "http") !== 0) {
            // Not a "link" that we can deal with.
            return $link;
        }
        
        // Check for non-HTTPS links on HTTPS connections
        if (!$this->server_bionet && $this->protocol === "https" && stripos($link, "https") === false) {
            // need to replace HTTP with HTTPS to avoid security warnings (as long as the target server has a valid certificate)
            return str_ireplace("http", "https", $link);
        }
        
        if (!$this->server_bionet) {
            // Not on bionet, all later operations don't apply
            return $link;
        }
        
        $val = $link;
        if ($this->server_bionet && stripos($val, "http") === 0) {
            $target_host = str_ireplace(".emsl.pnl.gov", ".bionet", $val);
            $target_host = str_ireplace(".pnl.gov", ".bionet", $target_host);
            $prev_protocol = stripos($target_host, "https") === 0 ? "https" : "http";
            if ($prev_protocol !== $this->protocol) {
                $target_host = str_ireplace($prev_protocol, $this->protocol, $target_host);
            }
            $val = $target_host;
        }
        
        return $val;
    }
}
