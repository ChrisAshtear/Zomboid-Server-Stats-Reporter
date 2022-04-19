<?php
function readzomboidtime()
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

?>