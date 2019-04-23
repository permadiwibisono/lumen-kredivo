<?php
namespace Pewe\Kredivo\Exceptions;
use Pewe\Kredivo\Exceptions\KredivoException;
/**
 * Invalid Server Key Kredivo Exceptions
 */
class InvalidServerKeyKredivoException extends KredivoException
{
	
	function __construct($error)
	{
		parent::__construct('Your server key is incorrect!', 'ERROR', $error);
	}
}