<?php

namespace Poli\Tarjeta;

interface Tarjeta {

	public function pagar(Transporte $transporte, $fecha_y_hora);
	public function recargar($monto);
	public function saldo();
}

