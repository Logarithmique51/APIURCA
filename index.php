<?php

 $string = file_get_contents("groupe_data.json");
 $json_a = json_decode($string,true);
 $newjson = array();
 foreach($json_a as $key => $val){
     $newkey = substr($key,1);
     $newjson[$newkey] = $val;
 }

 $fp = fopen('results_new.json', 'w');
    fwrite($fp, json_encode($newjson));
    fclose($fp);
     
?>
