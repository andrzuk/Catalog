<?php

if (isset($connection_error))
{
	include dirname(__FILE__) . '/../' . HELP_DIR . 'controller/init.php';
}
else
{
	include dirname(__FILE__) . '/../' . INSTALL_DIR . 'controller/init.php';
}


?>
