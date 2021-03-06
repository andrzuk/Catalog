<?php

$save_time = $this->mySqlDateTime;

$main_title = $record_item['main_title'];
$main_description = $record_item['main_description'];
$main_keywords = $record_item['main_keywords'];
$base_domain = $record_item['base_domain'];
$short_title = $record_item['short_title'];
$email_sender_address = $record_item['email_sender_address'];
$email_admin_address = $record_item['email_admin_address'];
$email_report_address = $record_item['email_report_address'];
$admin_name = $record_item['admin_name'];
$admin_login = $record_item['admin_login'];
$admin_password = sha1($record_item['admin_password']);

$domain_prefix = 'http://';
$domain_suffix = '/';

if (stristr($base_domain, $domain_prefix) === FALSE)
{
	$base_domain = $domain_prefix . $base_domain;
}
if (substr($base_domain, strlen($base_domain) - 1, 1) != $domain_suffix)
{
	$base_domain .= $domain_suffix;
}

$admin_names = explode(' ', $admin_name, 2);

$first_name = NULL;
$last_name = NULL;

if (is_array($admin_names))
{
	if (isset($admin_names[0])) $first_name = $admin_names[0];
	if (isset($admin_names[1])) $last_name = $admin_names[1];
}

$sql = array(
	array(
		'drop_constraints' => array(
			'ALTER TABLE `documents` DROP FOREIGN KEY `fk_documents_users`;',
			'ALTER TABLE `images` DROP FOREIGN KEY `fk_images_users`;',
			'ALTER TABLE `pages` DROP FOREIGN KEY `fk_pages_users`;',
			'ALTER TABLE `user_roles` DROP FOREIGN KEY `fk_roles_users`;',
			'ALTER TABLE `user_roles` DROP FOREIGN KEY `fk_roles_functions`;',
		),
	),
	array(
		'drop_tables' => array(
			'DROP TABLE IF EXISTS `admin_functions`;',
			'DROP TABLE IF EXISTS `categories`;',
			'DROP TABLE IF EXISTS `configuration`;',
			'DROP TABLE IF EXISTS `documents`;',
			'DROP TABLE IF EXISTS `hosts`;',
			'DROP TABLE IF EXISTS `images`;',
			'DROP TABLE IF EXISTS `logins`;',
			'DROP TABLE IF EXISTS `pages`;',
			'DROP TABLE IF EXISTS `query_params`;',
			'DROP TABLE IF EXISTS `registers`;',
			'DROP TABLE IF EXISTS `reminds`;',
			'DROP TABLE IF EXISTS `searches`;',
			'DROP TABLE IF EXISTS `users`;',
			'DROP TABLE IF EXISTS `user_messages`;',
			'DROP TABLE IF EXISTS `user_online`;',
			'DROP TABLE IF EXISTS `user_roles`;',
			'DROP TABLE IF EXISTS `visitors`;',
			'DROP TABLE IF EXISTS `visitor_counter`;',
		),
	),
	array(
		'create_tables' => array(
			"
				CREATE TABLE IF NOT EXISTS `admin_functions` (
				  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
				  `function` varchar(128) COLLATE utf8_polish_ci NOT NULL,
				  `meaning` varchar(512) COLLATE utf8_polish_ci NOT NULL,
				  `module` varchar(32) COLLATE utf8_polish_ci NOT NULL,
				  PRIMARY KEY (`id`),
				  UNIQUE KEY `module` (`module`)
				) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci AUTO_INCREMENT=1 ;			
			",
			"
				CREATE TABLE IF NOT EXISTS `categories` (
				  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
				  `level` tinyint(1) NOT NULL,
				  `parent_id` int(11) unsigned NOT NULL,
				  `permission` int(11) NOT NULL,
				  `item_order` int(11) NOT NULL,
				  `caption` varchar(128) CHARACTER SET utf8 NOT NULL,
				  `link` varchar(1024) CHARACTER SET utf8 NOT NULL,
				  `icon_id` int(11) unsigned NOT NULL,
				  `page_id` int(11) unsigned NOT NULL,
				  `visible` tinyint(1) NOT NULL,
				  `target` tinyint(1) NOT NULL,
				  `modified` datetime NOT NULL,
				  PRIMARY KEY (`id`)
				) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci AUTO_INCREMENT=1 ;
			",
			"
				CREATE TABLE IF NOT EXISTS `configuration` (
				  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
				  `key_name` varchar(30) NOT NULL,
				  `key_value` varchar(800) NOT NULL,
				  `meaning` varchar(128) DEFAULT NULL,
				  `field_type` int(11) NOT NULL,
				  `active` tinyint(1) NOT NULL,
				  `modified` datetime NOT NULL,
				  PRIMARY KEY (`id`),
				  UNIQUE KEY `key` (`key_name`)
				) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
			",
			"
				CREATE TABLE IF NOT EXISTS `documents` (
				  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
				  `section_id` int(11) unsigned NOT NULL,
				  `owner_id` int(11) unsigned NOT NULL,
				  `file_format` varchar(32) NOT NULL,
				  `file_name` varchar(512) NOT NULL,
				  `file_size` int(11) NOT NULL,
				  `doc_description` mediumtext,
				  `active` tinyint(1) NOT NULL DEFAULT '1',
				  `modified` datetime NOT NULL,
				  PRIMARY KEY (`id`),
				  KEY `fk_documents_users` (`owner_id`)
				) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
			",
			"
				CREATE TABLE IF NOT EXISTS `hosts` (
				  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
				  `server_ip` varchar(20) NOT NULL,
				  `server_name` varchar(256) NOT NULL,
				  `country` varchar(64) NOT NULL,
				  `city` varchar(64) NOT NULL,
				  PRIMARY KEY (`id`),
				  UNIQUE KEY `server_ip` (`server_ip`)
				) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
			",
			"
				CREATE TABLE IF NOT EXISTS `images` (
				  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
				  `section_id` int(11) unsigned NOT NULL,
				  `owner_id` int(11) unsigned NOT NULL,
				  `file_format` varchar(32) NOT NULL,
				  `file_name` varchar(512) NOT NULL,
				  `file_size` int(11) NOT NULL,
				  `picture_width` int(11) NOT NULL,
				  `picture_height` int(11) NOT NULL,
				  `picture_description` mediumtext,
				  `active` tinyint(1) NOT NULL DEFAULT '1',
				  `modified` datetime NOT NULL,
				  PRIMARY KEY (`id`),
				  KEY `fk_images_users` (`owner_id`)
				) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
			",
			"
				CREATE TABLE IF NOT EXISTS `logins` (
				  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
				  `agent` varchar(250) NOT NULL,
				  `user_ip` varchar(20) NOT NULL,
				  `user_id` int(11) unsigned NOT NULL,
				  `login` varchar(128) NOT NULL,
				  `password` varchar(128) NOT NULL,
				  `login_time` datetime NOT NULL,
				  UNIQUE KEY `id` (`id`)
				) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
			",
			"
				CREATE TABLE IF NOT EXISTS `pages` (
				  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
				  `main_page` tinyint(1) NOT NULL,
				  `system_page` tinyint(1) NOT NULL,
				  `category_id` int(11) unsigned NOT NULL,
				  `title` varchar(512) CHARACTER SET utf8 NOT NULL,
				  `contents` longtext CHARACTER SET utf8,
				  `author_id` int(11) unsigned NOT NULL,
				  `visible` tinyint(1) NOT NULL,
				  `modified` datetime NOT NULL,
				  PRIMARY KEY (`id`),
				  KEY `category_id` (`category_id`),
				  KEY `fk_pages_users` (`author_id`)
				) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci AUTO_INCREMENT=1 ;
			",
			"
				CREATE TABLE IF NOT EXISTS `query_params` (
				  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
				  `period_from` varchar(10) CHARACTER SET utf8 NOT NULL,
				  `period_to` varchar(10) CHARACTER SET utf8 NOT NULL,
				  `condition_field` varchar(20) CHARACTER SET utf8 NOT NULL,
				  `condition_operator` int(11) NOT NULL,
				  `condition_value` varchar(64) CHARACTER SET utf8 NOT NULL,
				  `addition_field` varchar(20) CHARACTER SET utf8 NOT NULL,
				  `addition_operator` int(11) NOT NULL,
				  `addition_value` varchar(64) CHARACTER SET utf8 NOT NULL,
				  `exceptions` longtext CHARACTER SET utf8,
				  `modified` datetime NOT NULL,
				  PRIMARY KEY (`id`)
				) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci AUTO_INCREMENT=1 ;
			",
			"
				CREATE TABLE IF NOT EXISTS `registers` (
				  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
				  `agent` varchar(250) NOT NULL,
				  `user_ip` varchar(20) NOT NULL,
				  `imie` varchar(128) NOT NULL,
				  `nazwisko` varchar(128) NOT NULL,
				  `login` varchar(128) NOT NULL,
				  `email` varchar(128) NOT NULL,
				  `password` varchar(128) NOT NULL,
				  `result` tinyint(1) NOT NULL,
				  `register_time` datetime NOT NULL,
				  UNIQUE KEY `id` (`id`)
				) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
			",
			"
				CREATE TABLE IF NOT EXISTS `reminds` (
				  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
				  `agent` varchar(250) NOT NULL,
				  `user_ip` varchar(20) NOT NULL,
				  `login` varchar(128) NOT NULL,
				  `email` varchar(128) NOT NULL,
				  `result` tinyint(1) NOT NULL,
				  `remind_time` datetime NOT NULL,
				  UNIQUE KEY `id` (`id`)
				) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
			",
			"
				CREATE TABLE IF NOT EXISTS `searches` (
				  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
				  `agent` varchar(250) NOT NULL,
				  `user_ip` varchar(20) NOT NULL,
				  `search_text` varchar(128) NOT NULL,
				  `search_time` datetime NOT NULL,
				  UNIQUE KEY `id` (`id`)
				) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
			",
			"
				CREATE TABLE IF NOT EXISTS `users` (
				  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
				  `user_login` varchar(32) NOT NULL,
				  `user_password` varchar(48) NOT NULL,
				  `imie` varchar(128) NOT NULL,
				  `nazwisko` varchar(128) NOT NULL,
				  `email` varchar(128) NOT NULL,
				  `status` int(11) NOT NULL DEFAULT '3',
				  `ulica` varchar(64) DEFAULT NULL,
				  `kod` varchar(6) DEFAULT NULL,
				  `miasto` varchar(64) DEFAULT NULL,
				  `pesel` varchar(16) DEFAULT NULL,
				  `telefon` varchar(48) DEFAULT NULL,
				  `data_rejestracji` datetime NOT NULL,
				  `data_logowania` datetime NOT NULL,
				  `data_modyfikacji` datetime NOT NULL,
				  `data_wylogowania` datetime NOT NULL,
				  `active` tinyint(1) NOT NULL,
				  PRIMARY KEY (`id`),
				  KEY `user_login` (`user_login`),
				  KEY `pesel` (`pesel`),
				  KEY `email` (`email`)
				) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
			",
			"
				CREATE TABLE IF NOT EXISTS `user_messages` (
				  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
				  `client_ip` varchar(20) NOT NULL,
				  `client_name` varchar(128) NOT NULL,
				  `client_email` varchar(256) NOT NULL,
				  `message_content` longtext NOT NULL,
				  `requested` tinyint(1) NOT NULL,
				  `send_date` datetime NOT NULL,
				  `close_date` datetime NOT NULL,
				  PRIMARY KEY (`id`)
				) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
			",
			"
				CREATE TABLE IF NOT EXISTS `user_online` (
				  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
				  `session` char(100) NOT NULL DEFAULT '',
				  `time` int(11) NOT NULL DEFAULT '0',
				  `user_id` int(11) unsigned NOT NULL,
				  PRIMARY KEY (`id`)
				) ENGINE=MEMORY DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
			",
			"
				CREATE TABLE IF NOT EXISTS `user_roles` (
				  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
				  `user_id` int(11) unsigned NOT NULL,
				  `function_id` int(11) unsigned NOT NULL,
				  `access` tinyint(1) NOT NULL,
				  PRIMARY KEY (`id`),
				  UNIQUE KEY `user_function` (`user_id`,`function_id`),
				  KEY `fk_roles_users` (`user_id`),
				  KEY `fk_roles_functions` (`function_id`)
				) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci AUTO_INCREMENT=1 ;
			",
			"
				CREATE TABLE IF NOT EXISTS `visitors` (
				  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
				  `visitor_ip` varchar(20) COLLATE utf8_polish_ci NOT NULL,
				  `http_referer` text COLLATE utf8_polish_ci,
				  `request_uri` text COLLATE utf8_polish_ci NOT NULL,
				  `visited` datetime NOT NULL,
				  UNIQUE KEY `id` (`id`),
				  KEY `visitor_ip` (`visitor_ip`)
				) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci AUTO_INCREMENT=1 ;
			",
			"
				CREATE TABLE IF NOT EXISTS `visitor_counter` (
				  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
				  `visitor_ip` varchar(20) NOT NULL,
				  `count` int(11) NOT NULL,
				  `time` varchar(15) NOT NULL,
				  `date` datetime NOT NULL,
				  UNIQUE KEY `id` (`id`),
				  KEY `visitor_ip` (`visitor_ip`),
				  KEY `date` (`date`)
				) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
			",
		),
	),
	array(
		'fill_data' => array(
			"
				INSERT INTO `admin_functions` (`id`, `function`, `meaning`, `module`) VALUES
				(1, 'config', 'Konfiguracja', 'config'),
				(2, 'users', 'Użytkownicy', 'users'),
				(3, 'functions', 'Funkcje', 'functions'),
				(4, 'ACL', 'Access Control List', 'roles'),
				(5, 'visitors', 'Odwiedziny', 'visitors'),
				(6, 'gallery', 'Galeria', 'images'),
				(7, 'documents', 'Dokumenty', 'docs'),
				(8, 'categories', 'Kategorie', 'categories'),
				(9, 'pages', 'Strony', 'pages'),
				(10, 'sites', 'Opisy', 'sites'),
				(11, 'messages', 'Wiadomości', 'messages'),
				(12, 'searches', 'Wyszukiwania', 'searches'),
				(13, 'registers', 'Rejestracje', 'registers'),
				(14, 'logins', 'Logowania', 'logins'),
				(15, 'passwords', 'Hasła', 'reminds'),
				(16, 'style', 'Wygląd', 'style');
			",
			"
				INSERT INTO `configuration` (`id`, `key_name`, `key_value`, `meaning`, `field_type`, `active`, `modified`) VALUES
				(1, 'main_title', '$main_title', 'tytuł strony internetowej', 2, 1, '$save_time'),
				(2, 'main_description', '$main_description', 'meta tag descriptions nagłówka strony', 2, 1, '$save_time'),
				(3, 'main_keywords', '$main_keywords', 'meta dane keywords strony internetowej', 2, 1, '$save_time'),
				(4, 'main_author', 'application logic & design: Andrzej Żukowski © 2013', 'autor serwisu - logiki biznesowej i designu', 2, 1, '$save_time'),
				(5, 'main_copyright', '$short_title', 'prawa autorskie serwisu', 1, 1, '$save_time'),
				(6, 'main_classification', 'CMS & MVC Project', 'klasyfikacja serwisu', 2, 1, '$save_time'),
				(7, 'main_publisher', '$base_domain', 'wydawca serwisu', 1, 1, '$save_time'),
				(8, 'main_page_topic', '$main_title', 'topic serwisu', 2, 1, '$save_time'),
				(9, 'base_domain', '$base_domain', 'domena (adres) serwisu', 1, 1, '$save_time'),
				(10, 'main_site_width', '1024px', 'szerokość strony w procentach lub pikselach', 1, 1, '$save_time'),
				(11, 'menu_panel_width', '18%', 'szerokość panela menu w procentach lub pikselach', 1, 1, '$save_time'),
				(12, 'content_panel_width', '80%', 'szerokość panela głównej treści w procentach lub pikselach', 1, 1, '$save_time'),
				(13, 'options_panel_visible', 'true', 'panel menu kontekstowego widoczny', 3, 1, '$save_time'),
				(14, 'options_panel_title', 'Opcje', 'tytuł panelu menu kontekstowego (panelu opcji)', 1, 1, '$save_time'),
				(15, 'menu_panel_visible', 'true', 'panel menu widoczny', 3, 1, '$save_time'),
				(16, 'menu_panel_title', 'Menu', 'tytuł panelu menu (kategorii)', 1, 1, '$save_time'),
				(17, 'search_panel_visible', 'true', 'panel szybkiego wyszukiwania widoczny', 3, 1, '$save_time'),
				(18, 'search_panel_title', 'Szukaj', 'tytuł panelu szybkiego wyszukiwania', 1, 1, '$save_time'),
				(19, 'stats_panel_visible', 'true', 'panel statystyk widoczny', 3, 1, '$save_time'),
				(20, 'stats_panel_title', 'Info', 'tytuł sekcji statystyk (tuż pod menu)', 1, 1, '$save_time'),
				(21, 'facebook_panel_visible', 'true', 'panel ikonek do facebooka widoczny', 3, 1, '$save_time'),
				(22, 'facebook_panel_title', 'Znajdź nas', 'tytuł panelu z ikonkami do facebooka', 1, 1, '$save_time'),
				(23, 'display_list_rows', '20', 'liczba wierszy listy na jednej stronie', 1, 1, '$save_time'),
				(24, 'description_length', '175', 'maksymalna długość opisu pozycji na liście znalezionych', 1, 1, '$save_time'),
				(25, 'page_pointer_band', '4', 'liczebność (połowa) paska ze wskaźnikami stron w pasku nawigacji', 1, 1, '$save_time'),
				(26, 'using_office_editor', 'false', 'użycie edytora tekstów typu WYSIWYG (układ Office-a)', 3, 1, '$save_time'),
				(27, 'starting_office_editor', 'false', 'automatyczne uruchomienie edytora tekstów typu WYSIWYG (układ Office-a)', 3, 1, '$save_time'),
				(28, 'office_editor_location', 'http://active-cms.eu/ckeditor/', 'położenie edytora Office-a wykorzystywanego do edycji artykułów', 2, 1, '$save_time'),
				(29, 'chart_library_path', '../pChart', 'ścieżka do biblioteki renderowania wykresów', 2, 1, '$save_time'),
				(30, 'chart_images_path', '../pChart/tmp/', 'ścieżka do wyrenderowanych obrazów wykresów', 1, 1, '$save_time'),
				(31, 'chart_width', '740', 'szerokość wykresu statystyk serwisu', 1, 1, '$save_time'),
				(32, 'chart_height', '350', 'wysokość wykresu statystyk serwisu', 1, 1, '$save_time'),
				(33, 'send_restricted_report', 'false', 'wysyłanie e-mailem raportów do admina o użyciu zabronionego słowa w formularzu', 3, 1, '$save_time'),
				(34, 'send_new_comment_report', 'false', 'wysyłanie e-mailem raportów do admina o pojawieniu się nowego komentarza', 3, 1, '$save_time'),
				(35, 'send_new_message_report', 'true', 'wysyłanie e-mailem raportów do admina o pojawieniu się nowej wiadomości', 3, 1, '$save_time'),
				(36, 'email_sender_name', 'Serwis $short_title - Mail Manager', 'nazwa konta e-mailowego serwisu', 1, 1, '$save_time'),
				(37, 'email_sender_address', '$email_sender_address', 'adres konta e-mailowego serwisu', 1, 1, '$save_time'),
				(38, 'email_admin_address', '$email_admin_address', 'adres e-mail administratora serwisu', 1, 1, '$save_time'),
				(39, 'email_report_address', '$email_report_address', 'adres e-mail odbiorcy raportów', 1, 1, '$save_time'),
				(40, 'email_report_subject', 'Raport serwisu $short_title', 'temat maila raportującego zdarzenie', 1, 1, '$save_time'),
				(41, 'email_report_body_1', 'Raport o zdarzeniu w serwisie $short_title', 'treść maila rapotującego - część przed zmiennymi', 2, 1, '$save_time'),
				(42, 'email_report_body_2', '(brak)', 'treść maila rapotującego - część za zmiennymi', 2, 1, '$save_time'),
				(43, 'email_createcnt_subject', 'Serwis $short_title - rejestracja konta', 'temat generowanego maila z potwierdzeniem rejestracji', 1, 1, '$save_time'),
				(44, 'email_createcnt_body_1', 'Dziękujemy za rejestrację w serwisie $short_title.\r\nParametry Twojego konta są następujące:', 'treść generowanego maila z potwierdzeniem rejestracji - przed parametrami', 2, 1, '$save_time'),
				(45, 'email_createcnt_body_2', 'Przypominamy, że hasło możesz zmienić po zalogowaniu, natomiast login musi pozostać nie zmieniony, gdyż jest identyfikatorem Twojego konta.', 'treść generowanego maila z potwierdzeniem rejestracji - po parametrach', 2, 1, '$save_time'),
				(46, 'email_editcnt_subject', 'Serwis $short_title - edycja konta użytkownika', 'temat generowanego maila edycji konta użytkownika', 2, 1, '$save_time'),
				(47, 'email_editcnt_body_1', 'Informujemy, że dokonałeś zmian w ustawieniach swojego konta.\r\nParametry Twojego konta są następujące:', 'wstęp e-maila do użytkownika o zmianie parametrów konta', 2, 1, '$save_time'),
				(48, 'email_editcnt_body_2', 'Przypominamy, że parametrów ''login'' i ''PESEL'' nie można zmienić, gdyż są one identyfikatorami konta w serwisie.', 'zakończenie e-maila do użytkownika o zmianie parametrów konta', 2, 1, '$save_time'),
				(49, 'email_remindpwd_subject', 'Serwis $short_title - nowe hasło do konta', 'temat generowanego maila z nowym hasłem', 1, 1, '$save_time'),
				(50, 'email_remindpwd_body_1', 'Na Twoją prośbę przesyłamy Ci hasło do konta w serwisie $short_title.\r\nOto Twój login, PESEL oraz nowe hasło:', 'treść generowanego maila z nowym hasłem - przed hasłem', 2, 1, '$save_time'),
				(51, 'email_remindpwd_body_2', 'Zaloguj się, a następnie zmień hasło na swoje własne.', 'treść generowanego maila z nowym hasłem - za hasłem', 2, 1, '$save_time');
			",
			"
				INSERT INTO `pages` (`id`, `main_page`, `system_page`, `category_id`, `title`, `contents`, `author_id`, `visible`, `modified`) VALUES
				(1, 1, 1, 0, 'Strona główna', '<h1>Serwis $short_title</h1><h2>$main_title</h2><h3>Strona główna</h3><p>$main_description</p>', 1, 1, '$save_time'),
				(2, 2, 1, 0, 'Kontakt', 'Szanowni Państwo! Mogą Państwo skontaktować się z nami, korzystając z poniższego formularza.', 1, 1, '$save_time'),
				(3, 0, 1, 0, 'Regulamin serwisu', 'Regulamin serwisu.', 1, 1, '$save_time'),
				(4, 0, 1, 0, 'Pomoc techniczna', 'Pomoc techniczna.', 1, 1, '$save_time'),
				(5, 0, 1, 0, 'Polityka plików cookies', 'Polityka plików cookies.', 1, 1, '$save_time');
			",
			"
				INSERT INTO `users` (`id`, `user_login`, `user_password`, `imie`, `nazwisko`, `email`, `status`, `ulica`, `kod`, `miasto`, `pesel`, `telefon`, `data_rejestracji`, `data_logowania`, `data_modyfikacji`, `data_wylogowania`, `active`) VALUES
				(1, '$admin_login', '$admin_password', '$first_name', '$last_name', '$email_admin_address', 1, '', '', '', '', '', '$save_time', '$save_time', '$save_time', '$save_time', 1);
			",
			"
				INSERT INTO `user_roles` (`id`, `user_id`, `function_id`, `access`) VALUES
				(1, 1, 1, 1),
				(2, 1, 2, 1),
				(3, 1, 3, 1),
				(4, 1, 4, 1),
				(5, 1, 5, 1),
				(6, 1, 6, 1),
				(7, 1, 7, 1),
				(8, 1, 8, 1),
				(9, 1, 9, 1),
				(10, 1, 10, 1),
				(11, 1, 11, 1),
				(12, 1, 12, 1),
				(13, 1, 13, 1),
				(14, 1, 14, 1),
				(15, 1, 15, 1),
				(16, 1, 16, 1);
			",
		),
	),
	array(
		'create_constraints' => array(
			'ALTER TABLE `documents` ADD CONSTRAINT `fk_documents_users` FOREIGN KEY (`owner_id`) REFERENCES `users` (`id`);',
			'ALTER TABLE `images` ADD CONSTRAINT `fk_images_users` FOREIGN KEY (`owner_id`) REFERENCES `users` (`id`);',
			'ALTER TABLE `pages` ADD CONSTRAINT `fk_pages_users` FOREIGN KEY (`author_id`) REFERENCES `users` (`id`);',
			'ALTER TABLE `user_roles` ADD CONSTRAINT `fk_roles_users` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);',
			'ALTER TABLE `user_roles` ADD CONSTRAINT `fk_roles_functions` FOREIGN KEY (`function_id`) REFERENCES `admin_functions` (`id`);',
		),
	),
);

?>