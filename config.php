<?php
require_once "vendor/autoload.php";
require_once "Bootstrap.php";

use \App\Ramal;

use DI\ContainerBuilder;
use Illuminate\Database\Capsule\Manager as Capsule;

$config = new \App\Ramal\Config();

$config->url = "http://localhost:8000";
$config->root = __DIR__;

$config->db_host = "mariadb";
$config->db_port = 3306;
$config->db_name = "app_db";
$config->db_user = "root";
$config->db_passwd = "root";


$containerBuilder = new ContainerBuilder();
$containerBuilder ->useAutowiring(true);
$containerBuilder ->useAttributes(true);


$containerBuilder->addDefinitions([
	 'setup.db' => [\App\Ramal\DB\Setup::class, 'get'],
	 'config'  => $config
]);







$bootstrap = new \Bootstrap();
$bootstrap->Add('config', $config);
$bootstrap->Add('container_builder', $containerBuilder);



$container = $containerBuilder->build();
$bootstrap->Add('container', $container);
//$config = new \App\Funvic\Config();

