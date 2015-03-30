<?php

/*
 * Model - pobiera dane dla podstrony z bazy
 */
class Stats_Model
{
	private $rows_list;
	private $table_name;
	
	public function __construct()
	{
		$this->table_name = 'visitors'; // nazwa głównej tabeli modelu w bazie
	}
	
	public function GetStats()
	{
		$this->rows_list = array();

		$query = "
			SELECT request_uri AS link, COUNT(*) AS licznik, SUBSTRING(request_uri, 30, 2) AS id, caption
			FROM " . $this->table_name . "
			INNER JOIN categories ON categories.id = SUBSTRING(request_uri, 30, 2)
			WHERE (request_uri LIKE '/index.php?route=category&id=_'
			OR request_uri LIKE '/index.php?route=category&id=__')
			AND visible=1
			GROUP BY request_uri
			ORDER BY licznik DESC
		";

		$result = mysql_query($query);

		if ($result)
		{
			while ($row = mysql_fetch_assoc($result))
			{
				$row['licznik'] = number_format($row['licznik'], 0, '', '.');
				$this->rows_list[] = $row;
			} 
			mysql_free_result($result);
		}

		return $this->rows_list;
	}	
}

?>
