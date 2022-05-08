<?php

require('luaparser.php');
include_once "parseData.php";

$env = parse_ini_file("/etc/environment",false,INI_SCANNER_RAW);
//get name of server
$SERVER_NAME = $env['ZOMBOID_SERVER_NAME'];

function readZTime()
{
	echo("trying file read");
	//read time file

	$filename = "sv/Saves/Multiplayer/".$GLOBALS['SERVER_NAME']."/map_t.bin";

	if(!is_file($filename))
	{
		throw new Exception('Invalid input file (' . $filename . ')');
	}

	
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

	//day and month are 0-values. $month=0 is january
	$day = intval($unpacked[31]) + 1;
	$month = intval($unpacked[35]) + 1;

	$output['day'] = $day;
	$output['month'] = $month;
	$output['dayssurvived'] = $unpacked[15];

	return $output;

	echo("DayOfMonth:".$day);
	echo("Month:".$month);
	echo("DaysSurvived:".$unpacked[15]);
}

function readZPlayerDataToDB()
{
	$pz_db = new SQLite3('sv/db/'.$GLOBALS['SERVER_NAME'].'.db');
	$db = new SQLite3('sv/Saves/Multiplayer/'.$GLOBALS['SERVER_NAME'].'/players.db');
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
}

function readSandbox()
{
	//Sandbox LUA file
	$sandbox = new LUAParser();

	try {
		// Add LUA keys that have to be present in the file
		// If one of the defined keys are missing an syntax error exception will be thrown
		$sandbox->addSyntaxKey('StartDay');
		$sandbox->addSyntaxKey('StartYear');
		$sandbox->addSyntaxKey('StartMonth');

		$sandbox->parseFile("sv/Server/".$GLOBALS['SERVER_NAME']."_SandboxVars.lua");
	}
	catch(Exception $e) {
		echo 'Exception: ',  $e->getMessage(), PHP_EOL;
	}

	return $sandbox;
}

?>