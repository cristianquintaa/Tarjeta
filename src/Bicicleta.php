<?php

namespace Poli\Tarjeta;

class Bicicleta extends Transporte{
	private $patente;
	public function __construct($patente){
		$this->tipo = "bici";
		$this->patente = $patente;
	}
}