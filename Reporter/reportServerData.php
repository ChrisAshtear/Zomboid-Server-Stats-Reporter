<?php

include "parseData.php";

//get name of server
$SERVER_NAME = "pz"

class MyDB extends SQLite3
{
    function __construct()
    {
        $this->open('Saves/Multiplayer/'.$SERVER_NAME.'/players.db');
    }
}

class MyPZ_DB extends SQLite3
{
    function __construct()
    {
        $this->open('db/'.$SERVER_NAME.'.db');
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
    $d = new parseData($row);
    $d->parse($row);
    $d->runQuery("Players",$d->sqlGetID("id","Players","id",$row['id'])); 
}
$d->closeSQL();
echo("trying file read");
//read time file
$filename = "Saves/Multiplayer/".$SERVER_NAME."/map_t.bin";
$handle = fopen($filename,"rb");
$contents = fread($handle, filesize($filename));
fclose($handle);
//var_dump($contents);
$unpacked = unpack("C40", $contents);
//var_dump($unpacked);
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

$server = parse_ini_file("Server/".$SERVER_NAME.".ini",false,INI_SCANNER_RAW);
echo($server["PublicName"]);
echo($server["PublicDescription"]);

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
