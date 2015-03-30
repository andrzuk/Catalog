<?php

/*
 * Klasa odpowiedzialna za obsługę nazw hostów na podst. adresów IP
 */

class Hosts
{
	public function find_host_name($host_address)
	{
		$host_name = NULL;
		$host_country = NULL;
		$host_city = NULL;

		$query = "SELECT server_name, country, city FROM hosts WHERE server_ip = '". $host_address ."'";
		$result = mysql_query($query);
		if ($result) 
		{
			if (mysql_num_rows($result) == 1)
			{
				$row = mysql_fetch_assoc($result);
				$host_name = $row['server_name'];
				$host_country = $row['country'];
				$host_city = $row['city'];
			}
			mysql_free_result($result);
		}
		if (empty($host_name) && empty($host_country) && empty($host_city)) // nie znalazł w tablicy - trzeba dopisać
		{
			$host_name = gethostbyaddr($host_address);
			$localization = $this->get_localization($host_address);
			$host_country = $localization['country'];
			$host_city = $localization['city'];
			$query = "INSERT INTO hosts VALUES (NULL, '". $host_address ."', '". $host_name ."', '". $host_country ."', '". $host_city ."')";
			mysql_query($query);
		}
		else if (!empty($host_name) && empty($host_country) && empty($host_city)) // znalazł tylko nazwę - trzeba uzupełnić
		{
			$localization = $this->get_localization($host_address);
			$host_country = $localization['country'];
			$host_city = $localization['city'];
			$query = "UPDATE hosts SET country = '". $host_country ."', city = '". $host_city ."' WHERE server_ip = '". $host_address ."'";
			mysql_query($query);
		}

		$host_name = str_replace(array("."), array(". "), $host_name);
		
		$host = array(
					'host_name' => $host_name,
					'host_country' => $host_country,
					'host_city' => $host_city,
				);
		
		return $host;
	}

	private function get_localization($host_address)
	{
		$localization = array('country' => NULL, 'city' => NULL);
		
		if (ip2long($host_address)== -1 || ip2long($host_address) === false) return $localization;

		$xml = file_get_contents("http://api.hostip.info/?ip=".$host_address);
		 
		preg_match("@<Hostip>(.*?)<gml:name>(.*?)</gml:name>@si", $xml, $x);
		$localization['city'] = $x[2];
		if (strpos($localization['city'], "Unknown") != false) $localization['city'] = "Nieznane";
		
		preg_match("@<countryName>(.*?)</countryName>@si", $xml, $y);
		$localization['country'] = $y[1];
		if (strpos($localization['country'], "Unknown") != false) $localization['country'] = "Nieznany";
		
		return $localization;
	}
}

?>