<?php

/*
 * View - generuje treść podstrony na podstawie zebranych danych
 */
class Stats_View
{
	public function ShowPage($row, $import)
	{
		$site_content = NULL;
		
		$site_content .= '<p>';
		
		$site_content .= '<table width="75%" align="center">';

		$site_content .= '<tr>';
		$site_content .= '<th width="10%" class="StatHeader">Lp.</th>';
		$site_content .= '<th width="80%" class="StatHeader">Kategoria lub artykuł</th>';
		$site_content .= '<th width="10%" class="StatHeader" style="text-align: right;">Odwiedzin</th>';
		$site_content .= '</tr>';

		$iter = 0;

		if (is_array($row))
		{
			foreach ($row as $k => $v)
			{
				$site_content .= '<tr>';
		
				foreach ($v as $key => $value)
				{
					if ($key == 'id') $category_id = $value;
					if ($key == 'link') $category_link = $value;
					if ($key == 'caption') $category_caption = $value;
					if ($key == 'licznik') $category_counter = $value;
				}
				$iter++;

				$site_content .= '<td class="StatData">'.$iter.'.'.'</td>';
				$site_content .= '<td class="StatData">'.'<a href="index.php?route=category&id='.$category_id.'">'.$category_caption.'</a>'.'</td>';
				$site_content .= '<td class="StatData" style="text-align: right;">'.$category_counter.'</td>';
		
				$site_content .= '</tr>';
			}
		}
		
		$site_content .= '</table>';

		$site_content .= '</p>';

		return $site_content;
	}
}

?>
