<?php

include_once "parseData.php";
include "getzdata.php";

$env = parse_ini_file("/etc/environment",false,INI_SCANNER_RAW);
//get name of server
$SERVER_NAME = $env['ZOMBOID_SERVER_NAME'];
chdir('/var/www/html');

//players db
//game db
try{
    readZPlayerDataToDB();
}
catch(Exception $e){
    echo($e);
    echo("Could not read zomboid db files. Is the zomboid server running & has a player been on it to force the server to create the save file?");
    return;
}

//map_t.bin
try{
    $timedata = readZTime();
}
catch(Exception $e){
    echo($e);
    echo("Could not read zomboid time file. Is the zomboid server running & has a player been on it to force the server to create the save file?");
    return;
}

//Server INI
$serverText = file_get_contents("sv/Server/".$GLOBALS['SERVER_NAME'].".ini");
$serverText = str_replace('#',';',$serverText);
//New parse_ini_file doesnt recognize # lines as comments so it wont read it otherwise.
$server = parse_ini_string($serverText,false,INI_SCANNER_RAW);
//Sandbox.lua
$sandbox = readSandbox();

//set current year.
$startYear = $sandbox->data['SandboxVars']['StartYear']-1+1993;//1993 is the year Zomboid takes place.
$startMonth = $sandbox->data['SandboxVars']['StartMonth'];
$startDay = $sandbox->data['SandboxVars']['StartDay'];
$daysIntoYear = date('z',strtotime($startMonth.'/'.$startDay)) + 1;
echo("days into year:".$daysIntoYear);
$daysSinceStart = $timedata['dayssurvived'];
$curYear = floor(($daysSinceStart + $daysIntoYear) / 365) + $startYear;

$sfield['sid'] = 1;
$sfield['dayofmonth'] = $timedata['day'];
$sfield['month'] = $timedata['month'];
$sfield['daysSinceStart'] = $timedata['dayssurvived'];
$sfield['sname'] = $server["PublicName"];
$sfield['desc'] = $server["PublicDescription"];
$sfield['maxPlayers'] = $server["MaxPlayers"];
$sfield['startDay'] = $startDay;
$sfield['startMonth'] = $startMonth;
$sfield['startYear'] = $startYear;
$sfield['year'] = $curYear;

$d = new parseData($sfield);
$d->parse($sfield);
$d->runQuery("Game",$d->sqlGetID("id","Game","id",$sfield['sid']));

$d->closeSQL();


?>
