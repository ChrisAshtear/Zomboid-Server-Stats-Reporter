<?php


class parseData
{
    // property declaration
    public $insQueryFields = '';
	public $insQueryVals = '';
	public $updQuery = "";
	private $conn;
	private $id = "";
  
  	public $transTable = []; // Translation table for WMI values -> SQL fields.

	public function __construct($data)
	{
      	
		$this->initConnection();
      	echo "connected";
      	if(isset($data))
        {  
          $this->loadCSVTables("translationTables.csv");
          $this->parse($data);
        }
      	echo "done";
	}
	
	public function initConnection()
	{
		$SQLSERVER = $_ENV['SQL_HOST'];
		$SQLUSER = $_ENV['SQL_USER'];
		$SQLPASS = $_ENV['SQL_PASSWORD'];
		$SQLDB = $_ENV['SQL_DATABASE'];
		$this->conn = new mysqli($SQLSERVER, $SQLUSER, $SQLPASS, $SQLDB);
	}
	
	public function parse($data)
	{
	$keys = array_keys($data);
    
	$updQuery = "";
	$insQueryFields = "";
	$insQueryVals = "";
      
    

	foreach($keys as $key)
	{
     	$sqlField = $this->csvDoTranslate($key);
      	//echo("lookup:".$key);
     	//var_dump( $sqlField);
      	$fieldName = $key;
      	if($sqlField != NULL)
        {
          	$fieldName = $sqlField[0];
        }
      	else
        {
          continue;
        }

		$updQuery .= $fieldName ."='".mysqli_real_escape_string($this->conn,$data[$key])."',";
		$k = mysqli_real_escape_string($this->conn,$fieldName);
      	$val = mysqli_real_escape_string($this->conn,$data[$key]);
		$insQueryFields .= $k . ",";
		$insQueryVals .= "'".$val."',";
		//$this->addAutofill($data[$key],$key);
	}
	
	$updQuery = substr($updQuery,0,-1);
	$insQueryFields = substr($insQueryFields,0,-1);
	$insQueryVals = substr($insQueryVals,0,-1);

	//echo($insQueryVals);

	$this->updQuery = $updQuery;
	$this->insQueryFields = $insQueryFields;
	$this->insQueryVals = $insQueryVals;
      
    echo("Parsed");
	}
	
	public function addAutofill($entry, $field)
	{
		$exceptions = ["Qty","Ret","date","id","Delivered","location","tech"];
		
		foreach($exceptions as $ex)
		{
			if($field == $ex)
			{
				return;
			}
		}
		
		//echo($entry . "-" . $field);
		$exists = false;
		
		$result = $this->sqlQuery("SELECT * FROM cc_autofill WHERE entry = '".$entry."' AND field = '".$field."'");
		$popularity = 0;
		while($rs = $result->fetch_array(MYSQLI_ASSOC)) {
			$exists = true;
			$popularity = $rs['popularity'];
		}
		
		$popularity++;
		if($exists)
		{
			$this->sqlQuery("UPDATE cc_autofill SET entry = '".$entry."', popularity = ".$popularity." WHERE field = '".$field."'");
		}
		else
		{
			$this->sqlQuery("INSERT INTO cc_autofill (entry,field) VALUES ('".$entry."', '".$field."')");
		}
		
	}
  
  	//Load Translation Table csv.
  	public function loadCSVTables($file)
    {
      	
      	$arr = []; 
      	if (($h = fopen($file, "r")) !== FALSE) 
        {
          echo("loading csv");
		  while (($data = fgetcsv($h, 1000, ",")) !== FALSE) 
          {		
            	$arr[$data[0]]= $data[1] . ":" . $data[2];	
          }
          fclose($h);
        }
      	echo("done");
      	$this->transTable = $arr;

    }
  
  	public function csvDoTranslate($inputField)
    {
      	$val = $this->transTable[$inputField];
      	if($val != "")
        {
           $retVal = explode(":",$val);
           return $retVal;
        }
      	else
        {
          return NULL;
        }

    }
	
	public function sqlQuery($query)
	{
		$result = $this->conn->query($query);
		return $result;
	}
  
  	//Query with response: 
  	//Input: SQL Query
  	//Output: NULL or Array with named keys cooresponding to SQL fields.
  	public function sqlQueryR($query)
    {
      	$result = $this->conn->query($query);
      	
      	return $result->fetch_array(MYSQLI_ASSOC);
    }
  
  	//This is used to determine if an entry already exists. If it does, we want to update it. if it doesnt, we want to insert.
  	//returns the ID if found, returns "" if not found. 
  	//ex. sqlGetID("id","cc_computers","MAC","00:00:DE:AD:BE:EF");
  	public function sqlGetID($idName,$tableName,$searchField,$searchVal)
    {
      $res = $this->sqlQueryR("Select ".$idName." FROM ".$tableName." WHERE ".$searchField."='".$searchVal."'");
      $retVal = "";
      
      if($res != null)
      {
         $retVal = $res[$idName];
      }
      
      return $retVal;
    }

	public function sqlRequest($query)
	{
		$result = $this->conn->query($query);
		$outp = "";
		//Note: does not support RETURN/NewLine character, use \n in the DB (twice).

		//user get keys or something similar to write the objects?
		while($rs = $result->fetch_array(MYSQLI_ASSOC)) {
		$keys = array_keys($rs);
		if ($outp != "") {$outp .= ",";}	
		$outp .= "{";
		foreach($keys as $key)
		{
		$outp .= '"'.$key.'":"'  . $rs[$key] . '",';
		}
		$outp = substr($outp,0,-1);
		$outp .= "}";
		}
		$outp ='{"records":['.$outp.']}';
		$this->conn->close();

		
		return $outp;
	}
	
	public function runQuery($tableName,$id)
	{
		$update = false;

		if($id != "")
		{
			$update = true;
		}
		if($update)
		{
			$query = "UPDATE ".$tableName." SET ".$this->updQuery." WHERE id=".$id;
			echo($query);
		}
		else
		{
			$query = "INSERT INTO ".$tableName." ( ".$this->insQueryFields.") VALUES ( ". $this->insQueryVals ." )";
		}
		$result = $this->conn->query($query);
		//$this->conn->close();
	}
  
  	public function closeSQL()
    {
      	$this->conn->close();
    }
}
?>
