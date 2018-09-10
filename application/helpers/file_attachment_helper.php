<?php  
    if (!defined('BASEPATH')) {
        exit('No direct script access allowed');
    }

function chmodr($path, $filemode) { 
    if (!is_dir($path)) {
        return chmod($path, $filemode); 
    }
    
    $dh = opendir($path); 
    while (($file = readdir($dh)) !== false) { 
        if($file != '.' && $file != '..') { 
            $fullpath = $path.'/'.$file; 
            if(is_link($fullpath)) {
                return FALSE; 
            }
            if(!is_dir($fullpath) && !chmod($fullpath, $filemode)) {
                return FALSE; 
            }
            if(!chmodr($fullpath, $filemode)) {
                return FALSE; 
            }
        } 
    } 

    closedir($dh); 

    if(chmod($path, $filemode)) {
        return TRUE; 
    } else {
        return FALSE; 
    }
}

function friendly_file_size($file_size_bytes) {
  $unit = "KB";
  if($file_size_bytes < 1024) {
     $nice_file_size = $file_size_bytes." bytes";
  }else if($file_size_bytes < pow(1024,2)) {
     $nice_file_size = round($file_size_bytes/1024,1);
     $unit = "KB";
  }else if($file_size_bytes < pow(1024,3)) {
     $nice_file_size = round($file_size_bytes/pow(1024,2),1);
     $unit = "MB";
  }else{
     $nice_file_size = round($file_size_bytes/pow(1024,3),2);
     $unit = "GB";
  }
  $file_size_string = ($unit == "GB") ? sprintf("%1\$.2f $unit",$nice_file_size) : sprintf("%1\$.1f $unit",$nice_file_size);
  return $file_size_string;
}
