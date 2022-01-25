<?php

include "parseData.php";
//have to push system environment values to a file on boot to allow them to be read by php
$env = parse_ini_file("/etc/environment",false,INI_SCANNER_RAW);
//get name of server
$SERVER_NAME = $env['ZOMBOID_SERVER_NAME'];
chdir('/var/www/html');

class PZplayer_DB extends SQLite3
{
    function __construct()
    {
        $this->open('sv/Saves/Multiplayer/'.$GLOBALS['SERVER_NAME'].'/players.db');
    }
}



class PZserver_DB extends SQLite3
{
    function __construct()
    {
        $this->open('sv/db/'.$GLOBALS['SERVER_NAME'].'.db');
    }
}


$player_db = new PZplayer_DB();
$server_db = new PZserver_DB();

//Read network players and update player entries in SQL db.

$result = $player_db->query('SELECT * FROM networkPlayers');

while ($row = $result->fetchArray()) 
{
	//The last connection values are stored in the serverdb table and stored by username. 
    $pzLookup = $server_db->query('SELECT username,lastConnection FROM whitelist WHERE username = "'.$row['username'].'"');
    $r = $pzLookup->fetchArray();
    $row['lastOnline'] = $r['lastConnection'];
	if($r['lastConnection'] == ""){$row['lastOnline']= date(DATE_ATOM, mktime(0, 0, 0, 7, 1, 2000));}//default time in case there is no value
    $d = new parseData($row);
    $d->parse($row);
    $d->runQuery("Players",$d->sqlGetID("id","Players","id",$row['id'])); 
}
$d->closeSQL();

//read binary time file
$filename = "sv/Saves/Multiplayer/".$GLOBALS['SERVER_NAME']."/map_t.bin";
$handle = fopen($filename,"rb");
$contents = fread($handle, filesize($filename));
fclose($handle);

$unpacked = unpack("C40", $contents);
// reset array keys
$unpacked = array_values($unpacked);
// this variable holds the size of *one* structure in the file
$block_size = 3;
// figure out the number of blocks in the file
$block_count = $file_size/$block_size;

$day = intval($unpacked[31]) + 1;//32nd byte is the CurrentDay value in the zomboid save file. Add+1 to it because day 1 has a value of 0.
$month = intval($unpacked[35]) + 1;//35th byte is the CurrentMonth value in the zomboid save file. Add+1 to it because january has a value of 0.
echo("DayOfMonth:".$day);
echo("Month:".$month);
echo("DaysSurvived:".$unpacked[15]);//15th byte is DaysSurvived.

//Read server settings from INI file. (Name and Description)
$server = parse_ini_file("sv/Server/".$GLOBALS['SERVER_NAME'].".ini",false,INI_SCANNER_RAW);
echo($server["PublicName"]);

    $sfield['sid'] = 1;
    $sfield['dayofmonth'] = $day;
    $sfield['month'] = $month;
    $sfield['daysSinceStart'] = $unpacked[15];
    $sfield['sname'] = $server["PublicName"];
    $sfield['desc'] = $server["PublicDescription"];
    $d = new parseData($sfield);
    $d->parse($sfield);
    $d->runQuery("Game",$d->sqlGetID("id","Game","id",$sfield['sid']));

$d->closeSQL();
?>
