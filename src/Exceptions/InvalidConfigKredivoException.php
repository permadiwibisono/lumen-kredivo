<?php
namespace Pewe\Kredivo\Exceptions;
use Pewe\Kredivo\Exceptions\KredivoException;
/**
 * Invalid Config Kredivo Exceptions
 */
class InvalidConfigKredivoException extends KredivoException
{
	
	function __construct()
	{
		parent::__construct('Please check your configs!', 'ERROR', [
			'message' => 'Please check your configs!',
			'kind' => 'InvalidConfigKredivoException'
		]);
	}
}