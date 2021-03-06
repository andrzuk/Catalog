<?php

/*
 * View - generuje treść podstrony na podstawie zebranych danych
 */
class Category_View
{
	public function ShowPage($row, $import)
	{
		$site_content = NULL;
		$site_modified = NULL;
		$author_login = NULL;
		
		foreach ($import as $i => $j)
		{
			if ($i == 'authors')
			{
				foreach ($j as $key => $value)
				{
					foreach ($value as $k => $v)
					{
						if ($k == 'id') $user_id = $v;
						if ($k == 'user_login') $user_login = $v;						
					}
					if ($user_id == $row['author_id']) $author_login = $user_login;
				}
			}
			if ($i == 'sound')
			{
				$sound_id = intval($j) ? intval($j) : NULL;
			}
		}

		if ($sound_id)
		{
			/*
			$site_content .= '<embed src="gallery/sounds/'.$sound_id.'" autostart="true" showcontrols="1" hidden="true" pluginspage="http://www.microsoft.com/Windows/MediaPlayer/" />';
			$site_content .= '<noembed>Niestety, Twoja przeglądarka nie odtwarza plików multimedialnych. Otwórz stronę w innej przeglądarce, np. Chrome.</noembed>';
			*/
			$site_content .= '<bgsound src="gallery/sounds/'.$sound_id.'" />';
		}
		
		if (is_array($row))
		{
			$site_content .= '<p style="text-align: justify;">';
		
			foreach ($row as $key => $value)
			{
				if ($key == 'contents') $site_content .= $value;
				if ($key == 'modified') $site_modified .= $value;
			}
			$site_content .= '</p>';
		}
		
		/*
		$site_content .= '<p class="PageSignature">';
		$site_content .= 'Modyfikacja: ' . $site_modified . ' Autor: ' . $author_login;
		$site_content .= '</p>';
		*/	
		$site_content = empty($row) ? NULL : $site_content;

		return $site_content;
	}
	
	public function ShowTitle($row)
	{
		$site_title = NULL;
		
		if (is_array($row))
		{
			foreach ($row as $key => $value)
			{
				if ($key == 'title') $site_title .= $value;
			}
		}
		
		return $site_title;
	}
}

?>
