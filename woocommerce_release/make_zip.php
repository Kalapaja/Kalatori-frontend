#!/usr/bin/php

<?php

$g=glob("./*.txt");
if(empty($g)) die("no files");

foreach($g as $file) do_store($file);

function do_store($file) {
    $s=file_get_contents($file);
//    echo $s."\n";
//    return;

    $TO="/tmp/UPUPO";
    exec("rm -r \"".$TO."\"");

    $DIR="./ps_dotpayment";
    $ZIP=$_SERVER['PWD']."/ps_dotpayment_".date("Y-m-d_H-i").".zip";


    $s=explode("\n",$s); foreach($s as $l) {
        if(!strstr($l,'|') || strstr($l,'#') || substr($l,0,1)=='@') continue;
        list($from,$to) = explode('|',$l);
	$from=trim($from);
	$to=trim($to);

	if($from=='ZIP') {
	    $to=str_replace('{date}',date("Y-m-d_H-i"),$to);
	    $ZIP=$_SERVER['PWD']."/".$to;
	    continue;
	}

	if($from=='DIR') {
	    $DIR=$to;
	    continue;
	}

	if(!strstr($from,'://')) $from=$DIR.'/'.$from;
	$to=$TO.'/'.$to;
        if(basename($to)=='*') $to=str_replace('*',basename($from),$to);
        echo "copy: [$from] -> [$to]\n";
        $dd=explode('/',dirname($to)); if($dd[0]=='') unset($dd[0]);
        $d=''; foreach($dd as $l) { $d.='/'.$l; if(!is_dir($d)) { mkdir($d); chmod($d,0777); }  }

        if(!strstr($from,'://') && !is_file($from)) die("Error: file not found [".$from."]");

        $o=file_get_contents($from);
        if(empty($o)) die("Error: empty file [".$from."]");

        foreach($s as $l) {
	    if(substr($l,0,1)!='@') continue;
	    list($a,$b)=explode('|',substr($l,1)); $a=trim($a," \t"); $b=trim($b," \t");
	    $o1=$o; $o=str_replace($a,$b,$o);
	    if($o1!=$o) echo "   --> replaced: ($a)=>($b)\n";
        }

        file_put_contents($to,$o);
        // copy($from,$to);
	chmod($to,0666);
    }

    echo "create: [$ZIP]\n";

    if(is_file($ZIP)) unlink($ZIP);
    exec("cd \"".$TO."\"; zip -r \"".$ZIP."\" \"./\"");
    chmod($ZIP,0666);
    copy($ZIP,"/tmp/".basename($ZIP));
}

?>