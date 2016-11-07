<?php

namespace Poli\Tarjeta;

class Baja implements Tarjeta {
  private $viajes = [];
  private $saldo = 0;
  protected $descuento;
  public $viajePlus=0;
  protected $ultimafecha = 0,$ultimabicipaga=0,$tiempomaxtransbordo=3600;
  protected $lunes, $dias = array(0 => "Lunes" , 1 => "Martes" , 2 => "Miercoles", 3 => "Jueves", 4 => "Viernes", 5 => "Sabado", 6 => "Domingo");
  public function __construct() {
    $this->descuento = 1;
    $this->lunes = strtotime("2016/01/04 00:00");
  }
  public function pagar(Transporte $transporte, $fecha_y_hora){
	if ($transporte->tipo() == "colectivo"){
      $aux1=strtotime($fecha_y_hora);
   	  $aux2=strtotime($this->ultimafecha);
	  $dia = $aux1 - $this->lunes;
      $a = $dia % 86400;
	  $dia = $dia - $a;
	  $dia = ($dia/86400) % 7;
      if(($dia==5 && $a>50400 && $a<79200) || ($dia==6 && $a>21600 && $a<79200) || $a<21600 || $a>79200 ){
      	$this->tiempomaxtransbordo=5400;
      } 
      else {
      	$this->tiempomaxtransbordo=3600;
      }

      if($this->ultimafecha == 0 || ($aux1-$aux2>$this->tiempomaxtransbordo) || $this->viajes[$this->ultimafecha]->getTransporte()->tipo() == $transporte->tipo()){
          $trasbordo = false;
        }
       else{
       	$trasbordo = true;
       }
      
      $monto = 0;
      if ($trasbordo){
        $monto = 2.5*$this->descuento;
      }
      else{
        $monto = 8*$this->descuento;
      }
      
      $this->saldo =  $this->saldo - $monto;
      $this->ultimafecha=$fecha_y_hora;
      if($this->saldo <0 ){
        if($this->viajePlus==2){
          $this->saldo= $this->saldo + $monto;
        }
        else{
          $this->viajePlus++;
          $this->saldo= $this->saldo + $monto;
          $this->viajes[] = new Viaje($transporte->tipo(), $monto, $transporte, strtotime($fecha_y_hora));
        }
      }
      else{
      	$this->viajes[] = new Viaje($transporte->tipo(), $monto, $transporte, strtotime($fecha_y_hora));
      }
    } 
    if ($transporte->tipo() == "bici"){
      	$aux1 = strtotime($fecha_y_hora);
		$aux2 = strtotime($this->ultimabicipaga);
		if($this->ultimabicipaga == 0 || ($aux1-$aux2>86400)){
			$this->saldo = $this->saldo-12;
        	if($this->saldo <0){
         	 $this->saldo= $this->saldo +12;
			} 
			else{
				$this->viajes[] = new Viaje($transporte->tipo(), 12, $transporte, strtotime($fecha_y_hora));
				$this->ultimabicipaga = $fecha_y_hora;
			}
		}
		else {
			$this->viajes[] = new Viaje($transporte->tipo(), 12, $transporte, strtotime($fecha_y_hora));
			$this->ultimabicipaga = $fecha_y_hora;
		}
        
    }   
} 
  public function recargar($monto){
    if ($monto == 272){
      $this->saldo = $this->saldo + 320;
    }
    else{
      if ($monto == 500){
        $this->saldo = $this->saldo + 640;
      }
      else{
        $this->saldo = $this->saldo + $monto;
      }
    }
    if($this->viajePlus > 0){
      $this->saldo = $this->saldo-($this->viajePlus*8);
      $this->viajePlus=0;
    }
  }
  public function saldo(){
    return $this->saldo;
  }
  public function viajePlus(){
  	return $this->viajePlus;
  }
}
