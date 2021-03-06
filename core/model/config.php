<?php

/*
 * Model - pobiera dane dla podstrony z bazy
 */
class Config_Model
{
	private $rows_list;
	private $row_item;
	private $table_name;
	
	private $mySqlDateTime;
	
	public function __construct()
	{
		$this->table_name = 'configuration'; // nazwa głównej tabeli modelu w bazie
		
		$timestampInSeconds = $_SERVER['REQUEST_TIME'];
		$this->mySqlDateTime = date("Y-m-d H:i:s", $timestampInSeconds);
	}
	
	public function SetPages($value, $limit, $show_rows)
	{
		$condition = empty($limit) ? NULL : ' AND id = ' . intval($limit);
		
		$filter = empty($value) ? NULL : " AND (key_name LIKE '%" . $value . "%' OR key_value LIKE '%" . $value . "%' OR meaning LIKE '%" . $value . "%')";

		$query = "SELECT COUNT(*) AS licznik FROM " . $this->table_name . " WHERE 1" . $condition . $filter;
		$result = mysql_query($query);
		if ($result)
		{
			$row = mysql_fetch_assoc($result); 
			$_SESSION['result_capacity'] = $row['licznik'];
			$_SESSION['page_counter'] = intval($row['licznik'] / $show_rows) + ($row['licznik'] % $show_rows > 0 ? 1 : 0);
			mysql_free_result($result);
		}
	}
	
	public function GetOne($id)
	{
		$this->row_item = array();

		$query = "SELECT * FROM " . $this->table_name . " WHERE id=" . intval($id);
		$result = mysql_query($query);
		if ($result)
		{
			$row = mysql_fetch_assoc($result); 
			$this->row_item = $row;
			mysql_free_result($result);
		}
		return $this->row_item;
	}
	
	public function GetAll($limit, $params)
	{
		$condition = empty($limit) ? NULL : ' AND id = ' . intval($limit);
		
		$filter = empty($_SESSION['list_filter']) ? NULL : " AND (key_name LIKE '%" . $_SESSION['list_filter'] . "%' OR key_value LIKE '%" . $_SESSION['list_filter'] . "%' OR meaning LIKE '%" . $_SESSION['list_filter'] . "%')";

		$this->rows_list = array();

		$query = 	"SELECT * FROM " . $this->table_name . " WHERE 1" . $condition . $filter .
					" ORDER BY " . $params['sort_field'] . " " . $params['sort_order'] . 
					" LIMIT " . $params['start_from'] . ", " . $params['show_rows'];		

		$result = mysql_query($query);

		if ($result)
		{
			while ($row = mysql_fetch_assoc($result))
			{
				$this->rows_list[] = $row;
			} 
			mysql_free_result($result);
		}
		
		return $this->rows_list;
	}
	
	public function Add($record_item)
	{
		$query = "INSERT INTO " . $this->table_name . " VALUES (NULL, '" . 
					mysql_real_escape_string($record_item['key_name']) . "', '" . 
					mysql_real_escape_string($record_item['key_value']) . "', '" . 
					mysql_real_escape_string($record_item['meaning']) . "', '" . 
					$record_item['field_type'] . "', '" . 
					$record_item['active'] . "', '" . 
					$this->mySqlDateTime . "')";
		mysql_query($query);
		
		return mysql_affected_rows();
	}
	
	public function Edit($record_item, $id)
	{
		$query = "UPDATE " . $this->table_name . 
					" SET key_name='" . mysql_real_escape_string($record_item['key_name']) . 
					"', key_value='" . mysql_real_escape_string($record_item['key_value']) . 
					"', meaning='" . mysql_real_escape_string($record_item['meaning']) . 
					"', field_type='" . $record_item['field_type'] . 
					"', active='" . $record_item['active'] . 
					"', modified='" . $this->mySqlDateTime . 
					"' WHERE id=" . intval($id);
		mysql_query($query);
		
		return mysql_affected_rows();
	}
	
	public function GetLast()
	{
		$this->row_item = array();

		$query = "SELECT * FROM " . $this->table_name . " ORDER BY id DESC LIMIT 0, 1";
		$result = mysql_query($query);
		if ($result)
		{
			$row = mysql_fetch_assoc($result); 
			$this->row_item = $row;
			mysql_free_result($result);
		}
		return $this->row_item;
	}
	
	public function Remove($id)
	{
		$query = "DELETE FROM " . $this->table_name . " WHERE id=" . intval($id);
		mysql_query($query);

		return mysql_affected_rows();
	}
}

?>