<?php
	function autoload($class) {
		$files = '../'.str_replace('\\','/',$class.'.class.php');
		require $files;
	}

	spl_autoload_register('autoload');