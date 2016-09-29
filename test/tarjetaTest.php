<?php
namespace Poli\Tarjeta;

class TarjetaTest extends \PHPUnit_Framework_TestCase {

  protected $tarjeta,$colectivo1;	

  public function setup(){
			$this->tarjeta = new Baja();
			$this->colectivo1 = new Colectivo("144 Negro", "Rosario Bus");}	

  public function testRecargar() {
    $this->tarjeta->recargar(272);
    $this->assertEquals($this->tarjeta->saldo(), 320, "Cuando cargo 272 deberia tener finalmente 320");
    $this->tarjeta = new Baja();
    $this->tarjeta->recargar(500);
    $this->assertEquals($this->tarjeta->saldo(), 640, "Cuando cargo 500 deberia tener finalmente 640");
  }

 public function testPagar() {
  	$this->tarjeta->recargar(272);
  	$this->tarjeta->pagar($this->colectivo1, "2016/09/28 23:51");
  	$this->assertEquals($this->tarjeta->saldo(), 312, "Cuando recargo 272 y pago un colectivo deberia tener finalmente 312");
  }
}

?>
