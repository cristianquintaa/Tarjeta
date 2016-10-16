<?php
namespace Poli\Tarjeta;

class TarjetaTest extends \PHPUnit_Framework_TestCase {

	protected $tarjeta,$tarjeta2, $tarjeta3,$colectivo1, $colectivo2;	

	public function setup(){
		$this->tarjeta = new Baja();
		$this->tarjeta2 = new medioBoleto();
		$this->tarjeta3 = new paseLibre();
		$this->colectivo1 = new Colectivo("144 Negro", "Rosario Bus");
		$this->colectivo2 = new Colectivo("142 Rojo", "Rosario Bus");
		$this->bici = new Bicicleta(666);
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
		$this->assertEquals($this->tarjeta->saldo(), 89.5 , "Si cargo 100 y pago dos colectivos y uno con transbordo deberia tener 89.5");
	}
	public function testSinTransbordo(){
		$this->tarjeta->recargar(100);
		$this->tarjeta->pagar($this->colectivo1, "2016/09/10 19:04");
		$this->tarjeta->pagar($this->colectivo2, "2016/09/10 20:30");
		$this->assertEquals($this->tarjeta->saldo(), 84, "Si cargo 100 y pago dos colectivos sin transbordo deberia tener 84");
	}
	public function testPagarBici(){
		$this->tarjeta->recargar(100);
		$this->tarjeta->pagar($this->bici,"2016/09/10 12:00");
		$this->assertEquals($this->tarjeta->saldo(), 88, "Si cargo 100 y pago una bici deberia tener 88");
	}
	public function testPagarSinSaldo(){
		$this->tarjeta->pagar($this->bici,"2016/09/10 12:00");
		$this->assertEquals($this->tarjeta->saldo(), 0, "Si intento pagar una bici sin saldo no deberia poder");
		$this->tarjeta->pagar($this->colectivo1,"2016/09/10 12:00");
		$this->tarjeta->pagar($this->colectivo2,"2016/09/10 14:00");
		$this->tarjeta->pagar($this->colectivo1,"2016/09/10 20:00");
		$this->assertEquals($this->tarjeta->viajePLus(), 2, "Si intento pagar 3 colectivos sin saldo solo tendria que poder pagar 2");
		$this->tarjeta->recargar(100);
		$this->assertEquals($this->tarjeta->saldo(), 84, "Si cargo 100 y habia usado dos viajes plus deberia tener 84");
	}
	public function testPagarMedioBoleto(){
		$this->tarjeta2->recargar(100);
		$this->tarjeta2->pagar($this->colectivo1, "2016/09/10 19:04");
		$this->assertEquals($this->tarjeta2->saldo(), 96, "Si cargo 100 y pago un colectivo con medio deberia tener 96");
	}
	public function testPagarPaseLibre(){
		$this->tarjeta3->recargar(5);
		$this->tarjeta3->pagar($this->colectivo2, "2016/10/09 04:19");
		$this->assertEquals($this->tarjeta3->saldo(), 5, "Si pago un colectivo con pase libre no deberia cobrarme");
	}
}
?>
