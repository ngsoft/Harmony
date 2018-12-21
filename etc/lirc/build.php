#!/usr/bin/env php
<?php
$target = __DIR__ . "/flirc.conf";
$clean = $target.".dist";
//Build from keymaps dir
$keymaps = __DIR__ . "/flirc.keymaps";
$kdir = __DIR__. "/keymaps";
$irexecdir = __DIR__ .'/irexec.conf.d';

$irexec_data = [
    "prog"      =>  "harmony",
    "remote"    =>  "FLIRC",
    "repeat"    =>  0,
    "delay"     =>  0,
    "config"    =>  "hrmy-send %s"
];
$dups = [];

foreach ([$clean, $kdir, $irexecdir] as $file) {
    if(!file_exists($file)) throw new Exception("Cannot find all the files", 1);
}

$keymap_file_contents = [];
$target_contents = file_get_contents($clean);
$clean_contents = $target_contents;

foreach(scandir($kdir) as $file){
    if(preg_match('/\.conf$/', $file) && ($contents = file_get_contents("$kdir/$file"))){
        $irexec_contents = [];
        $contents = str_replace("#", ";", $contents);
        if(($data = @parse_ini_string($contents, false, INI_SCANNER_RAW))){
            foreach($data as $replace => $new){
                $target_contents = preg_replace_callback('/('.$replace.'\s)[\s]+([0-9]+)\n/', function($matches) use($new){
                    $k = $matches[1];
                    $v = $matches[2];
                    return $new.str_repeat(" ", 24 - strlen($new)).$v."\n";
                    
                }, $target_contents);
                //$target_contents = str_replace("$replace ", "$new ", $target_contents);
                $keymap_file_contents[]= $replace . str_repeat(" ", 24 - strlen($replace)) . "=    " . $new;
                if(in_array($new, $dups)) continue;
                $irexec_contents[] = implode("\n", [
                    "\n# <$new>\nbegin",
                    str_repeat(" ", 4) . sprintf("prog    =  %s", $irexec_data["prog"]),
                    str_repeat(" ", 4) . sprintf("remote  =  %s", $irexec_data["remote"]),
                    str_repeat(" ", 4) . sprintf("button  =  %s", $new),
                    str_repeat(" ", 4) . sprintf("repeat  =  %s", $irexec_data["repeat"]),
                    str_repeat(" ", 4) . sprintf("delay   =  %s", $irexec_data["delay"]),
                    str_repeat(" ", 4) . sprintf("config  =  %s", sprintf($irexec_data["config"], $new)),
                    "end\n"
                ]);
                $dups[] = $new;
            }
            if(!empty($irexec_contents)) file_put_contents("$irexecdir/$file", implode("\n", $irexec_contents));
        }       
    }
}

if($clean_contents !== $target_contents) file_put_contents($target, $target_contents);
if(!empty($keymap_file_contents)) file_put_contents($keymaps, implode("\n", $keymap_file_contents));