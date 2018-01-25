<?php
namespace Pewe\Kredivo\Facades;
use Illuminate\Support\Facades\Facade;
/**
* 
*/
class Kredivo extends Facade
{
	/**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor() { 
    	return 'Pewe\Kredivo\Kredivo'; 
    }
}