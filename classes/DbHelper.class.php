<?php

namespace IO;

use Exception;
use PDO;
use PDOException;

class DbHelper
{
    public string $Host;
    public string $Username;
    public string $Password;
    public int $Port;
    public string $DbName;

    public $Pdo;

    /**
     * DEFAULT CONSTRUCTOR
     * Perform extensive tasks here like object Initialization
     */
    function __construct(string $host, string $username, string $password, string $dbname, int $port = 3306)
    {
        $this -> Host = $host;
        $this -> Username = $username;
        $this -> Password = $password;
        $this -> DbName = $dbname;
        $this -> Port = $port;

        $provider = "mysql:host={$this->Host}; dbname={$this->DbName}; charset=utf8";
        
        $this -> Pdo = new PDO($provider, $this -> Username, $this -> Password);
        $this -> Pdo -> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $this -> Pdo -> setAttribute(PDO::MYSQL_ATTR_INIT_COMMAND, "SET sql_mode=(SELECT REPLACE(@@sql_mode,'ONLY_FULL_GROUP_BY',''))");
    }
    /**
     * Count all rows from a table
     */
    public function CountRows(string $table) : int
    {
        $sth = $this -> Pdo -> prepare("SELECT COUNT(*) FROM $table");
        $sth -> execute();
        $total = $sth -> fetchColumn();

        return $total;
    }
    /**
     * Select all rows from a table
     * then returns an ASSOC ARRAY
     * of MULTIPLE rows
     */
    public function SelectAll(string $table)
    {  
        $sql = "SELECT * FROM $table";
        $sth = $this -> Pdo -> prepare($sql); 
        $sth->execute();

        $result = $sth -> fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }
    /**
     * Select all row from a table
     * then return an ASSOC ARRAY
     * of SINGLE row
     */
    public function SelectRow(string $table)
    {  
        $sql = "SELECT * FROM $table LIMIT";
        $sth = $this -> Pdo -> prepare($sql); 
        $sth->execute();

        $result = $sth -> fetchAll(PDO::FETCH_ASSOC) ?: [];
        return $result;
    }
    /**
     * Select all row from a table with matching CONDITION
     * then return an ASSOC ARRAY
     * of SINGLE row
     */
    public function SelectRow_Where(string $table, array $conds) : array
    {  
        // Get all column keys
        $cols = array_keys($conds);

        // Generate prepared params (?) string
        $cols_param = [];

        foreach ($cols as $c)
        {
            array_push($cols_param, "$c=?");
        }

        // Build params array as single string 
        $cols_param_str = join(" AND ", $cols_param);

        // Get all condition values
        $vals = array_values($conds);

        // Build the query string
        $sql = "SELECT * FROM $table WHERE $cols_param_str LIMIT 1";
        
        // Execute the query and fetch the result set as array
        $sth = $this -> Pdo -> prepare($sql);

        // Bind values to query
        $sth -> execute($vals);

        $result = $sth -> fetch(PDO::FETCH_ASSOC) ?: [];
        return $result;
    }
    /**
     * Select all row from a table with matching CONDITION
     * then return an ASSOC ARRAY
     * of MULTIPLE row
     */
    public function SelectAll_Where(string $table, array $conds) : array
    {  
        // Get all column keys
        $cols = array_keys($conds);

        // Generate prepared params (?) string
        $cols_param = [];

        foreach ($cols as $c)
        {
            array_push($cols_param, "$c=?");
        }

        // Build params array as single string 
        $cols_param_str = join(" AND ", $cols_param);

        // Get all condition values
        $vals = array_values($conds);

        // Build the query string
        $sql = "SELECT * FROM $table WHERE $cols_param_str";
        echo $sql;
        
        // Execute the query and fetch the result set as array
        $sth = $this -> Pdo -> prepare($sql);

        // Bind values to query
        $sth -> execute($vals);

        $result = $sth -> fetch(PDO::FETCH_ASSOC) ?: [];
        return $result;
    }
    /**
     * Select all from from offsetX to offsetY.
     * Useful for pagination
     */
    public function SelectLimitOffset(string $table, int $x, int $y)
    {
        if ($x < 1)
            $x = 0;

        $sql = "SELECT * FROM $table ORDER BY id LIMIT $x, $y";
        $sth = $this -> Pdo -> prepare($sql);
        $sth -> execute();

        $result = $sth -> fetchAll(PDO::FETCH_ASSOC) ?: [];
        return $result;
    }

    /**
     * Select All matching rows with matching EQUALS condition
     * then returns and ASSOC ARRAY.
     * 
     * Use condition like: $cond = ["key1" => "val1", "key2" => "val2"];
     */
    /*
    public function SelectAll_Where(string $table, array $conditions, bool $singleRow = false) : array
    {   
        $keys = []; 
        $where = [];

        $key_index = 0;

        foreach($conditions as $conds)
        {
            foreach($conds as $k => $v)
            { 
                $param_name = "{$k}{$key_index}";
                // Param names (p) must be the same as column names (c), but with different suffix
                //                   c     p
                array_push($where, "{$k}=:{$param_name}"); 
                $keys[$key_index] = $param_name;

                $key_index ++;
            }
        }

        // reset key index
        $key_index = 0;

        $limit = $singleRow ? " LIMIT 1" : "";

        // Build the query string
        $sql = "SELECT * FROM {$table} WHERE " . join(" AND ", $where) . $limit;
        // echo $sql . "<br>";
 
        // Bind parameters
        $sth = $this -> Pdo -> prepare($sql); 

        foreach($conditions as $conds)
        {
            foreach($conds as $k => $v)
            { 
                // Get all individual keys and values
                // Then bind them
                $sth -> bindValue(":$keys[$key_index]", $v);
                // echo "sth -> bindValue(':$keys[$key_index]', $v)";
                $key_index ++;
            }
        }

        $sth->execute();
 
        if ($singleRow)
           return $sth ->fetch(PDO::FETCH_ASSOC) ?: [];
        else
            return $sth -> fetchAll(PDO::FETCH_ASSOC);  
    }
    */

    // INSERT SINGLE ROW
    public function InsertRow(string $table, array $data) : bool
	{
		// make sure data has contents
		if(count($data) <= 0)
		{
			return false;
		}
		
		// collect all col names
		$col = array_keys($data);
		$cols = join(",", $col);
		
		// create param bindings (?)
		$params = array();
		
		foreach ($data as $d)
		{
			array_push($params, "?");
		}
		
		// join params as single string
		$paramBindings = join(",", $params);
		
		// build the query
		$sql = "INSERT INTO $table($cols) VALUES($paramBindings);";
		  
        // the actual data to insert
        $dataBindings = array_values($data);

		// execute 
		try
		{
			$sth = $this -> Pdo -> prepare($sql);
			$sth -> execute($dataBindings);
			 
			return true;
		}
		catch(PDOException $pdx)
		{ 
			return false;
		}
	}

    // INSERT SINGLE ROW
    public function UpdateWhereEquals(string $table, array $data, array $conditions) : bool
	{
		// make sure data has contents
		if(count($data) <= 0)
		{
			return false;
		}
		 
        // get column names and values for data array
        $data_col_names = array_keys($data); 

        // create placeholders for data
        $data_placeholder = array();

        foreach($data_col_names as $cols)
        {
            $placeholder = "$cols=:data_$cols";
            array_push($data_placeholder, $placeholder);
        }

        // build data placeholder string
        $data_placeholder_str = join(",", $data_placeholder);

        // create placeholder for conditions
        $cond_col_names = array_keys($conditions); 
        $cond_placeholder = array();

        foreach($cond_col_names as $col) { array_push($cond_placeholder, "$col=:cond_$col  "); }

        $cond_placeholder_str = join("AND ", $cond_placeholder);

        $sql = "UPDATE $table SET $data_placeholder_str WHERE $cond_placeholder_str";
        //echo $sql ."<br>";
		$sth = $this -> Pdo -> prepare($sql);

        $paramBindings = array();

        // bind data to sth
        foreach($data as $keys => $vals) { $paramBindings[":data_$keys"] = $vals; }

        // bind condition to sth
        foreach($conditions as $keys => $vals) { $paramBindings[":cond_$keys"] = $vals; }
  
        // Execute
		try
		{
            $sth -> execute($paramBindings);
			//echo "Success" ;
            return true;
		}
		catch(PDOException $pdx)
		{
            echo $pdx -> getMessage();
            return false;
		}
	}

    /**
     * Checks if an array or result set is empty
     */
    public function IsResultSetEmpty(array $resultSet)
    {
        return (empty($result) || count($resultSet) <= 0);
    }
}

?>