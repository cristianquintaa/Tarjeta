<?php

namespace Poli\Tarjeta;

class Baja implements Tarjeta {
  private $viajes = [];
  private $saldo = 0;
  protected $descuento;
  public $viajePlus=0;
  public function __construct() {
    $this->descuento = 1;
  }
  public function pagar(Transporte $transporte, $fecha_y_hora){
    if ($transporte->tipo() == "colectivo"){
      $trasbordo = false;
      if (count($this->viajes) > 0){
        if (end($this->viajes)->tiempo() - strtotime($fecha_y_hora) < 3600) {
          $trasbordo = true;
        }
      }
      $monto = 0;
      if ($trasbordo){
        $monto = 2.64*$this->descuento;
      }
      else{
        $monto = 8*$this->descuento;
      }
      $this->viajes[] = new Viaje($transporte->tipo(), $monto, $transporte, strtotime($fecha_y_hora));
      $this->saldo =  $this->saldo - $monto;
      if($this->saldo <0 ){
        if($this->viajePlus==2){
          echo "La tarjeta no tiene saldo. No puede viajar.<br>";
          $this->saldo= $this->saldo + $monto;
        }
        else{
          echo "Usted uso un viaje plus <br>";
          $this->viajePlus++;
          $this->saldo= $this->saldo + $monto;
        }
      }
    } 
    else {
      if ($transporte->tipo() == "bici"){
        $this->viajes[] = new Viaje($transporte->tipo(), 12, $transporte, strtotime($fecha_y_hora));
        $this->saldo = $this->saldo-12;
        if($this->saldo <0){
          echo "La tarjeta no tiene saldo. No puede viajar. <br>";
          $this->saldo= $this->saldo +12;
        }
      }
    }   
  }
  public function recargar($monto){
    if ($monto == 272){
      $this->saldo = $this->saldo + 320;
    }
    else{
      if ($monto = 500){
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
    echo "El saldo actual es de: ". $this->saldo, "<br>";
  }
  public function saldo(){
    return $this->saldo;
  }
  public function viajesRealizados(){ 
    echo "Estos fueron los viajes realizados: <br>";
    return $this->viajes; 
  }
}
