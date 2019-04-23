<?php
namespace Pewe\Kredivo\Exceptions;
/**
 * Kredivo Base Exception
 */
class KredivoException extends \Exception
{
	private $status;
	private $error;
	function __construct($message, $status = 'ERROR', $error = null)
	{
		parent::__construct($message);
		$this->status = $status;
		$this->error = $error;
	}

	public function getErrors()
	{
		$errors = [
			'status' => $this->status,
			'message' => $this->getMessage()
		];
		if(!is_null($this->error)) $errors['error'] = $this->error;
		return $errors;
	}
}