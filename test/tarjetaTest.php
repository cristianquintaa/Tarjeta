<?php
namespace Poli\Tarjeta;

class TarjetaTest extends \PHPUnit_Framework_TestCase {

	protected $tarjeta,$colectivo1;	

	public function setup(){
		$this->tarjeta = new Baja();
		$this->tarjeta2 = new Baja();
		$this->colectivo1 = new Colectivo("144 Negro", "Rosario Bus");
		$this->colectivo2 = new Colectivo("142 Rojo", "Rosario Bus");
	}
	public function testRecargar() {
		$this->tarjeta->recargar(272);
		$this->assertEquals($this->tarjeta->saldo(), 320, "Cuando cargo 272 deberia tener finalmente 320");
		$this->tarjeta = new Baja();
		$this->tarjeta->recargar(500);
		$this->assertEquals($this->tarjeta->saldo(), 640, "Cuando cargo 500 deberia tener finalmente 640");
	}
	public function testPagar() {
		$this->tarjeta = new Baja();
		$this->tarjeta->recargar(272);
  		$this->tarjeta->pagar($this->colectivo1, "2016/09/10 23:51");
  		$this->assertEquals($this->tarjeta->saldo(), 312, "Cuando recargo 272 y pago un colectivo deberia tener finalmente 312");
  	}
	public function testTransbordo(){
		$this->tarjeta = new Baja();
		$this->tarjeta->recargar(100);
		$this->tarjeta->pagar($this->colectivo1, "2016/09/10 19:04");
		$this->tarjeta->pagar($this->colectivo2, "2016/09/10 19:30");
		$this->assertEquals($this->tarjeta->saldo(), 89,36, "Si cargo 100 y pago dos colectivos y uno con transbordo deberia tener 89,36");
	}
	public function testSinTransbordo(){
		$this->tarjeta2->recargar(272);
		$this->tarjeta2->pagar($this->colectivo1, "2016/09/10 19:04");
		$this->tarjeta2->pagar($this->colectivo2, "2016/09/10 20:30");
		$this->assertEquals($this->tarjeta2->saldo(), 304, "Si cargo 272 y pago dos colectivos sin transbordo deberia tener 304");
	}
}
?>
