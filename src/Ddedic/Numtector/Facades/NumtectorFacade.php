<?php namespace Ddedic\Numtector\Facades;
 
use Illuminate\Support\Facades\Facade;
 
class NumtectorFacade extends Facade {
 
  /**
   * Get the registered name of the component.
   *
   * @return string
   */
  protected static function getFacadeAccessor() { return 'numtector'; }
 
}