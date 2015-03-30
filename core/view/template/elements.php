<?php

/*
 * Klasa odpowiedzialna za generowanie elementów strony (nagłówek, stopka, ...)
 */

class Elements
{
	private $site_header;
	private $site_footer;

	public function set_header()
	{
		$header = '<a href="index.php" class="LogoImage"><img src="img/banner.png" class="Logo" width="100%" alt="logo" /></a>';

		$this->site_header = $header;
	}

	public function set_footer()
	{
		$footer = NULL;
		
		$footer .= '<table class="Footer" width="100%" cellpadding="0">';
		
		$footer .= '<tr>';
		$footer .= '<td width="100%" colspan="3" style="text-align: center">';
		$footer .= '<div id="project">';
		$footer .= 'Projekt <i>„Nowoczesne technologie szansą rozwoju zawodowego i aktywizacji społecznej osób niepełnosprawnych”</i><br />';
		$footer .= 'Zakup współfinansowany ze środków Europejskiego Funduszu Rozwoju Regionalnego w ramach Programu Operacyjnego Innowacyjna Gospodarka, 2007-2013';
		$footer .= '</div>';
		$footer .= '<div id="slogan">';
		$footer .= '<i>„Dotacje na innowacje. Inwestujemy w waszą przyszłość”</i>';
		$footer .= '</div>';
		$footer .= '</td>';
		$footer .= '</tr>';
/*		
		$footer .= '<tr>';
		$footer .= '<td width="33%" style="text-align: left">';
		$footer .= '<a href="http://www.um.warszawa.pl/" class="MenuLink">Oficjalny Portal Miasta Stołecznego Warszawa</a>';
		$footer .= '</td>';
		$footer .= '<td width="34%" style="text-align: center">';
		$footer .= '<a href="http://www.funduszeeuropejskie.gov.pl/slownik/strony/europejskifunduszrozwojuregionalnego(efrr).aspx" class="MenuLink">Europejski Fundusz Rozwoju Regionalnego</a>';
		$footer .= '</td>';
		$footer .= '<td width="33%" style="text-align: right">';
		$footer .= '© '.date("Y").' <a href="http://harpo.com.pl" class="MenuLink">HARPO Sp. z o. o.</a> Wszystkie prawa zastrzeżone.';
		$footer .= '</td>';
		$footer .= '</tr>';
*/		
		$footer .= '<tr>';
		$footer .= '<td width="100%" colspan="3" style="text-align: center">';
		$footer .= 'Serwis używa cookies. Więcej informacji na stronie: "<a href="index.php?route=page&id=95" class="FooterLink">Polityka cookies</a>".';
		$footer .= '</td>';
		$footer .= '</tr>';
		
		$footer .= '<tr>';
		$footer .= '<td width="100%" colspan="3" style="text-align: center">';
		$footer .= '© '.date("Y").' <a href="https://plus.google.com/113303165754486219878?rel=author" class="FooterLink">Andrzej Żukowski</a>. Wszystkie prawa zastrzeżone.';
		$footer .= '</td>';
		$footer .= '</tr>';
		
		$footer .= '</table>';
		
		$this->site_footer = $footer;
	}

	public function show_header()
	{
		return $this->site_header;
	}

	public function show_footer()
	{
		return $this->site_footer;
	}
}

?>
