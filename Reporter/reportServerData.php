<?php

include "parseData.php";

$env = parse_ini_file("/etc/environment",false,INI_SCANNER_RAW);
//get name of server
$SERVER_NAME = $env['ZOMBOID_SERVER_NAME'];
chdir('/var/www/html');

class MyDB extends SQLite3
{
    function __construct()
    {
        $this->open('sv/Saves/Multiplayer/'.$GLOBALS['SERVER_NAME'].'/players.db');
    }
}

class MyPZ_DB extends SQLite3
{
    function __construct()
    {
        $this->open('sv/db/'.$GLOBALS['SERVER_NAME'].'.db');
    }
}


$db = new MyDB();
$pz_db = new MyPZ_DB();

$result = $db->query('SELECT * FROM networkPlayers');

while ($row = $result->fetchArray()) 
{
    $pzLookup = $pz_db->query('SELECT username,lastConnection FROM whitelist WHERE username = "'.$row['username'].'"');
    $r = $pzLookup->fetchArray();
    $row['lastOnline'] = $r['lastConnection'];
	if($r['lastConnection'] == ""){$row['lastOnline']= date(DATE_ATOM, mktime(0, 0, 0, 7, 1, 2000));}
    $d = new parseData($row);
    $d->parse($row);
    $d->runQuery("Players",$d->sqlGetID("id","Players","id",$row['id'])); 
}
$d->closeSQL();
echo("trying file read");
//read time file
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

$day = intval($unpacked[31]) + 1;
$month = intval($unpacked[35]) + 1;
echo("DayOfMonth:".$day);
echo("Month:".$month);
echo("DaysSurvived:".$unpacked[15]);

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
