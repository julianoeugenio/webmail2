<?php

class BD_FB_EMAIL
{
	protected $host = "C:/fire_bird/EMAIL.FDB";
	protected $user = "SYSDBA";
	protected $pswd = "masterkey";
	protected $con = null;
	
	function __construct(){} //m�todo construtor
	
	#m�todo que inicia conexao 
	function open(){

		$this->con = ibase_connect($this->host,$this->user,$this->pswd) or die(utf8_encode("Erro ao efetuar a conex�o<br>" . ibase_errmsg()));

		return $this->con;

	}

	#m�todo que encerra a conexao
	function close(){

		ibase_close($this->con);
		//pg_close($this->con);

	}
function getcon(){
return $this->con;
}
	
}

