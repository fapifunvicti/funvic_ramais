<?php
namespace App\Ramal\DB;


use \Illuminate\Database\Capsule\Manager as Capsule;

class Setup {

	public static function get(){
		global $config;
		$capsule = new Capsule();

		$capsule->addConnection([
		    'driver'    => 'mysql',
		    'host'      =>  $config->db_host,
		    'database'  =>  $config->db_name,
		    'username'  =>  $config->db_user,
		    'password'  =>  $config->db_passwd,
		    'charset'   => 'utf8mb4',
		    'collation' => 'utf8mb4_unicode_ci',
		    'prefix'    => '',
		]);

		// Make this Capsule instance available globally via static methods
		$capsule->setAsGlobal();

		// Setup the Eloquent ORM...
		$capsule->bootEloquent();
		return $capsule;
	}
}