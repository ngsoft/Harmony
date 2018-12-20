#!/usr/bin/env php
<?php
$target = __DIR__ . "/flirc.conf";
$clean = $target.".dist";
//Build from keymaps dir
$keymaps = __DIR__ . "/flirc.keymaps";
$kdir = __DIR__. "/keymaps";


foreach ([$clean, $kdir] as $file) {
    if(!file_exists($file)) throw new Exception("Cannot find all the files", 1);
}

$keymap_file_contents = [];
$target_contents = file_get_contents($clean);
$clean_contents = $target_contents;

foreach(scandir($kdir) as $file){
    if(preg_match('/\.conf$/', $file) && ($contents = file_get_contents("$kdir/$file"))){
        $contents = str_replace("#", ";", $contents);
        if(($data = @parse_ini_string($contents, false, INI_SCANNER_RAW))){
            print_r($data);
            foreach($data as $replace => $new){
                $target_contents = str_replace("$replace ", "$new ", $target_contents);
                $keymap_file_contents[]= $replace . str_repeat(" ", 24 - strlen($replace)) . "=    " . $new;
            }
        }       
    }
}

if($clean_contents !== $target_contents) file_put_contents($target, $target_contents);
if(!empty($keymap_file_contents)) file_put_contents($keymaps, implode("\n", $keymap_file_contents));