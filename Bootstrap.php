<?php

class Bootstrap {

	private array $classes = [];

	public function Add(string $name, object $obj){
			$this->classes[$name] = $obj;
	}

	public function Start(){
		$config = $this->classes['config'];

		session_name($config->session_nome);
		
		if(session_status() !=  PHP_SESSION_ACTIVE){
			session_start();
		}

		date_default_timezone_set($config->timezone);

		switch($config->modo){
			case 'debug':
		        ini_set('display_errors', 1);
		        ini_set('display_startup_errors', 1);
		        error_reporting(E_ALL);
			break;

			default:
			case 'release':
		        ini_set('display_errors', 0);
        		ini_set('display_startup_errors', 0);
        		error_reporting(E_ALL & ~E_NOTICE);
        	break;
		}

		if(isset($tz)) {
		    unset($tz);
		}


		$tz = date_default_timezone_get();

		if(strcmp($tz, ini_get("date.timezone")) != 0){
		    setlocale(LC_ALL, $config->lang);
		}


	}

	public function End(){

	}

}