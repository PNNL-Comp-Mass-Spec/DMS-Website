<?php
        //header( "Location: $protocol://$host/dmsdevci4/");

        if (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') {
                $protocol = 'https';
        }
        $host = $_SERVER['HTTP_HOST'];
        header( "Location: $protocol://$host/dmsdevci4/public/");
?>

