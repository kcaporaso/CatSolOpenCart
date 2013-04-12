<?php
final class DB {
    
	private $driver;
	
	public function __construct($driver, $hostname, $username, $password, $database) {
		if (file_exists(DIR_DATABASE . $driver . '.php')) {
			require_once(DIR_DATABASE . $driver . '.php');
		} else {
			exit('Error: Could not load database file ' . $driver . '!');
		}
				
		$this->driver = new $driver($hostname, $username, $password, $database);
	}
		
  	public function query($sql) {
		return $this->driver->query($sql);
  	}
	
	public function escape($value) {
		return $this->driver->escape($value);
	}
	
  	public function countAffected() {
		return $this->driver->countAffected();
  	}

  	public function getLastId() {
		return $this->driver->getLastId();
  	}

  	
  	// from IMS
	public function add ($table, $data, $delayed = false) {
		if (empty($data)) {
			trigger_error("No data was supplied for insertion into the database."); return;
		}

		$q = "INSERT ".($delayed?"DELAYED":"")." INTO $table ";

		$data = $this->wrap_row($data);

		$fields = array_keys($data);

		# wrap column names in special quotes
		foreach ($fields as $i => $f)
			$fields[$i] = "`$f`";

		$values = array_values($data);

		$fields_string = implode(",", $fields);
		$values_string = implode(",", $values);

		$q = "$q ($fields_string) VALUES ($values_string);";

		$r = $this->ims_query($q);

		return $r;
    }
    
    
    // from IMS
	public function update ($table, $data, $where) {
	    
		if (empty($data)) {
			trigger_error("No data was supplied for update()."); return;
		}

		if (empty($where)) {
			trigger_error("No WHERE clause specified for update()."); return;
		}

		$q = "UPDATE $table SET ";

		$data = $this->wrap_row($data);

		foreach($data as $column => $value) {
			$set_pairs[] = $column . "=" . $value;
		}

		$q .= implode(", ", $set_pairs);
		$q .= " WHERE " . $where;

		$r = $this->ims_query($q);

		return $r;
    }

    
    // from IMS
	public function delete ($table, $where) {
	    
		if (empty($where)) {
			trigger_error("No WHERE clause specified for delete()."); return;
		}

		$q = "DELETE FROM $table WHERE $where";

		$r = $this->ims_query($q);

		return $r;
		
	}    
    
    
    // from IMS
	public function wrap_row ($data) {
	    
		foreach ($data as $key => $value)
			$parsed[$key] = $this->wrap_value($value);

		return $parsed;
		
	}
	
	
	// from IMS
	public function unwrap_row ($row) {
	    
		foreach ($row as $key => $value)
			$unwrapped[$key] = $this->unwrap_value($value);

		return $unwrapped;
		
	}
		
	
	// from IMS
	public function wrap_value ($value) {

	    if (strcmp('%TRUE%', $value) == 0) {
			$wrapped = 1;

		} elseif (strcmp('%FALSE%', $value) == 0) {
			$wrapped = 0;

		} elseif (is_bool($value)) {
			$wrapped = $value?'1':'0';

		} elseif (is_null($value) || strcmp($value,'%NULL%') == 0) {
			$wrapped = "NULL";

		} elseif (is_int($value)) {
			$wrapped = $value; // no transformation on value

		} elseif (is_float($value) || is_double($value)) {
			$wrapped = $value; // no transformation on value

		} elseif (is_array($value)) {
			$wrapped = "'".DB::encode_array($value)."'";

		} elseif (is_string($value)) {

			$wrapped = DB::decode_html_string($value); // change &quot; back to ", &lt; back to <, etc.
			$wrapped = $this->escape($wrapped); // magic_quotes_gpc MUST BE OFF FOR THIS TO WORK!!!!
			$wrapped = "'$wrapped'"; // wrap the string in single quotes

		} else { // auto-detect

			if (!is_numeric($value)) {
				$wrapped = DB::decode_html_string($value); // change &quot; back to ", &lt; back to <, etc.

				$wrapped = $this->escape($wrapped); // magic_quotes_gpc MUST BE OFF FOR THIS TO WORK!!!!

				$wrapped = "'$wrapped'"; // wrap the string in single quotes
			} else {
				//var_dump($value);
				$wrapped = $value;
			}
		}

		return $wrapped;
		
	}
	
	
	// from IMS
	public function unwrap_value ($value) {

		if (strpos($value, "%arr%:") === 0)
			return $this->decode_array($value);

		return $value;
	}	

	
	// from IMS
	public function encode_array ($array) {
	    
		/* In the current MySQL implementation, we convert the array into a single string,
		   with elements seperated by semicolons. Any semicolons found in the existing values
		   are converted to "%semicolon%" beforehand. */

		if (!is_array($array))
			return $array;

		foreach($array as $key => $value)
			$array[$key] = str_replace(";", "%semicolon%", $value);

		$multivalue_string = implode(";", $array);

		return "%arr%:" . $multivalue_string;
		
	}
	
	
	// from IMS
	public function decode_array ($multivalue) {
	    
		/* In the current implementation, a multi-value column is stored as a string,
		   with each value separated by ;'s. So for example, this function would take
		   "Apple;Bannana;Orange" and return array("Apple", "Bannana", "Orange"). */

		$multivalue = substr($multivalue, strlen("%arr%:"));

		$array = explode(";", $multivalue);

		foreach($array as $key => $value)
			$array[$key] = str_replace("%semicolon%", ";", $value);

		return $array;
		
	}	
	
	
	// from IMS
	public function decode_html_string ($encoded_string) {
	    
		$decoded_string =	str_replace(	array(	"&gt;",	"&lt;",	"&quot;",	"&amp;",	"&eacute;",		"&Eacute;",		"&ecirc;",	"&egrave;",	"&agrave;",	"&ccedil;",	"&ugrave;",	"&icirc;",	"&ucirc;",	"&#153;",	"&laquo;",	"&raquo;",	"&ocirc;"),
											array(	">",	"<",	"\"",		"&",		"?",			"?",			"?",		"?",		"?",		"?",		"?",		"?",		"?",		"?",		"?",		"?",		"?"),
											$encoded_string);

		return $decoded_string;
		
	}
	
	
	// from IMS
	public function get_column ($table, $col, $where, $orderby=null) {
		
		$orderby_clause = ($orderby)? "ORDER BY {$orderby}" : null;
		
		$q = "SELECT $col FROM $table WHERE $where $orderby_clause";

		$r = $this->ims_query($q);
		$col = $this->fetch_col($r, $col);

		return $col;
		
	}


	// from IMS
	public function get_record ($table, $where, $additional_cols = null) {
	    
		$q = "SELECT *".($additional_cols?",$additional_cols":"")." FROM $table WHERE $where";

		$r = $this->ims_query($q);

		$c = mysql_num_rows($r);

		if ($c == 0) {
			return false;
		} elseif ($c > 1) {
			trigger_error("get_record() matched more than one row. Query:" . $q); return;
		}

		$row = $this->fetch_row($r);

		return $row;
		
	}


	// from IMS
	public function get_multiple ($table, $where = null, $orderby = null, $limit = null, $startat = null, $additional_cols = null) {
	    
		$q = "SELECT *".($additional_cols?",$additional_cols":"")." FROM $table";

		if (!empty($where))
			$q .= " WHERE $where";

		if (!empty($orderby))
			$q .= " ORDER BY $orderby";

		if (!empty($limit))
			$q .= " LIMIT ".($startat?"$startat,":"")."$limit";

		$r = $this->ims_query($q);

		$data = $this->fetch_all($r);

		if (!$data) // if there are no results, return an empty array to help prevent errors further down with foreach()
			$data = array();
        
		return $data;
		
	}


	// from IMS
	public function get_last ($table, $where, $by) {
	    
		$all = $this->get_multiple($table, $where, "$by DESC");

		return $all[0];
		
	}

	
	// from IMS
	public function get_last_insert_id () {
		
		return $this->getLastId();
		
	}	
	
	
	// from IMS
	public function count ($table, $where = null, $group_by = null) {
	    
		$parts[] = "SELECT COUNT(*) FROM $table";

		if (!empty($where)) 	$parts[] = "WHERE $where";
		else 					$parts[] = "WHERE 1";
		
		if (!empty($group_by)) $parts[] = "GROUP BY $group_by";

		$q = implode(' ', $parts);

		$r = $this->ims_query($q);
		$count = $this->fetch_col($r);

		return (int) $count;
		
	}
	
	
	// from IMS
	public function fetch_col ($r, $col = 0) {
	    
		$row = mysql_fetch_array($r, MYSQL_BOTH);
		$col = $row[$col];

		$col = $this->unwrap_value($col);

		return $col;
		
	}
	
	
	// from IMS
	public function fetch_all ($r) {
	    
		while ($row = $this->fetch_row($r)) {
			$data[] = $row;
		}

		return $data;
		
	}
	
	
	// from IMS
	public function fetch_row ($r) {
	    
		if (!$r) // this should have caused an error in query()
			return false;
		
		$row = mysql_fetch_assoc($r);

		if ($row) // if no more data left in $r, $row is false
			$row = $this->unwrap_row($row);

		return $row;
		
	}
	
	
	public function ims_query ($q) {
	    
		$q = trim($q);

		$r = mysql_query($q, $this->driver->connection);

		if ($r) {
			return $r;
		} else {
			trigger_error("Query failed : ". $q);
			return false;
		}
		
    }	
		
  	
}
?>
