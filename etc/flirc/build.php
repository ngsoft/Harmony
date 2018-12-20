#!/usr/bin/env php
<?php
$target = __DIR__ . "/flirc.conf";
$clean = $target.".dist";
$keymaps = __DIR__ . "/flirc.keymaps";

foreach ([$clean, $keymaps] as $file) {
    if(!file_exists($file)) throw new Exception("Cannot find all the files", 1);
}

$contents = file_get_contents($clean);
$compare = $contents;
if($data = @parse_ini_file($keymaps, false, INI_SCANNER_RAW)){
    foreach($data as $replace => $new){
        $contents = str_replace("$replace ", "$new ", $contents);
    }
}

if($compare !== $contents) file_put_contents($target, $contents);