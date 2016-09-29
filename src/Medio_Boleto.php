<?php

namespace Poli\Tarjeta;

class Medio_Boleto extends Baja {
	public function __construct(){
		$this->descuento = 0.5;
}
