<?php
namespace App\Ramal;

class Config {
	public string $url;
	public string $root;


	public string $db_host = "localhost";
	public int $db_port = 3006;
	public string $db_name = "";
	public string $db_user = "";
	public string $db_passwd = "";

	public string $timezone = "America/Sao_Paulo";
	public string $lang = "pt_BR";
	public string $modo = "debug";

	public string $session_nome = "funramal_sess";
	public int    $session_tempo = 300; // 5 min
}