<?php
namespace App\Libraries;

class URL_updater {

    private $protocol = "http";
    private $server_bionet = false;

    /**
     * Constructor
     */
    function __construct() {
        $serverHttpsState = \Config\Services::superglobals()->server("HTTPS");
        $this->protocol = isset($serverHttpsState) && $serverHttpsState == "on" ? "https" : "http";
        // TODO: May be better to check for the 'pnl.gov' string in the name?
        $serverName = \Config\Services::superglobals()->server("SERVER_NAME");
        $this->server_bionet = stripos($serverName, "bionet") !== false;
    }

    /**
     * Transform the value to a URL if it starts with doi: or http,
     * or if it matches a standard MassIVE or ProteomeXchange accession
     * @param string $value
     * @param int $colIndex
     * @return string
     */
    function get_doi_link(string $value, int $colIndex): string {

        $urls = [];

        if (preg_match('/^doi:/i', $value)) {
             // Assure that $value does not have any spaces
            $urls[$value] = "https://doi.org/" . str_replace(' ', '', $value);
        }
        else if (preg_match('/^https?:\/\//', $value)) {
            $urls[$value] = $value;
        }
        else if (preg_match_all('/MSV\d{9,}|PXD\d{6,}/', $value, $matches)) {
            // Matched MSV000085977 and/or PXD021009
            foreach ($matches as $value) {
                foreach ($value as $accession) {
                    if (preg_match('/^MSV/', $accession)) {
                        $urls[$accession] = "https://massive.ucsd.edu/ProteoSAFe/dataset.jsp?accession=$accession";
                    } else {
                        $urls[$accession] = "http://proteomecentral.proteomexchange.org/cgi/GetDataset?ID=$accession";
                    }
                }
            }

        }

        if (count($urls) == 0) {
            return $value;
        }

        $str = "";
        foreach ($urls as $key => $url) {
            if (strlen($str) > 0) {
                $str .= ' and ';
            }

            $str .= "<a href='$url' target='External$colIndex'>$key</a>";
        }

        return $str;
    }

    /**
     * Auto-update the link to change from http to https or vice versa,
     * depending on the target host name
     * @param string $link
     * @return string
     */
    function fix_link(string $link): string {
        if (stripos($link, "http") !== 0) {
            // Not a "link" that we can deal with.
            return $link;
        }

        // Check for non-HTTPS links on HTTPS connections
        if (!$this->server_bionet && $this->protocol === "https" && stripos($link, "https") === false) {
            // Need to replace HTTP with HTTPS to avoid security warnings (as long as the target server has a valid certificate)
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
?>
