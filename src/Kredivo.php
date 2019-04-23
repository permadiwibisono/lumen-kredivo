<?php 
namespace Pewe\Kredivo;
use GuzzleHttp\Exception\RequestException;
use \GuzzleHttp\Client as Client;
use Pewe\Kredivo\Exceptions\InvalidConfigKredivoException as InvalidConfigException;
use Pewe\Kredivo\Exceptions\KredivoException;
/**
* 
*/
class Kredivo
{
	private static $SANDBOX_URL ='https://sandbox.kredivo.com';
	private static $API_URL ='https://api.kredivo.com';
	private static $VERSION;

	private $is_production;
	private $server_key;
	private $push_uri;
	private $user_cancel_uri;
	private $back_to_store_uri;
	
	function __construct(array $configs)
	{
		$this->_validateConfig($configs);
		self::$VERSION =  array_key_exists('version', $configs)? $configs['version']: 'v2';
		$this->is_production = array_key_exists('is_production', $configs)? $configs['is_production']: true;
		$this->server_key = $configs['server_key'];
		$this->push_uri = $configs['push_uri'];
		$this->user_cancel_uri = $configs['user_cancel_uri'];
		$this->back_to_store_uri = $configs['back_to_store_uri'];
	}

	private function _validateConfig(array $configs)
	{
		if(!array_key_exists('server_key', $configs) || is_null($configs['server_key']))
			throw new InvalidConfigException();
		if(!array_key_exists('push_uri', $configs) || is_null($configs['push_uri']))
			throw new InvalidConfigException();
		if(!array_key_exists('user_cancel_uri', $configs) || is_null($configs['user_cancel_uri']))
			throw new InvalidConfigException();
		if(!array_key_exists('back_to_store_uri', $configs) || is_null($configs['back_to_store_uri']))
			throw new InvalidConfigException();
	}

	private function getServerKey()
	{
		return $this->server_key;
	}

	private function getApiUrl()
	{
		return ($this->is_production ? self::$API_URL : self::$SANDBOX_URL);
	}

	private function getVersion()
	{
		return self::$VERSION;
	}

	private function request(){
		return new Client([
			'base_uri'=>$this->getApiUrl(),
			'headers'=> [
				'Content-Type' =>	'application/json',
				'Accept' =>	'application/json'
			]
		]);
	}

	private function getResponse($response)
	{
		$response_array = (array)$response;
		if($response_array['status'] === 'OK')
			return $response_array;
		$message = array_key_exists('error', $response_array)?
			$response_array['error']->message : 'Unhandled error!';
		$error = array_key_exists('error', $response_array)?
			$response_array['error'] : null;
		throw new KredivoException(
			$message,
			$response_array['status'],
			$error
		);
	}

	public function getRelativeUrl($route)
	{
		return 'kredivo/'.self::$VERSION.'/'.$route;
	}

	public function checkout(array $payloads)
	{
		try {
			$payloads['server_key'] = $this->getServerKey();
			if(!array_key_exists('push_uri', $payloads))
				$payloads['push_uri'] = $this->push_uri;
			if(!array_key_exists('user_cancel_uri', $payloads))
				$payloads['user_cancel_uri'] = $this->user_cancel_uri;
			if(!array_key_exists('back_to_store_uri', $payloads))
				$payloads['back_to_store_uri'] = $this->back_to_store_uri;
			$response = $this->request()->post($this->getRelativeUrl('checkout_url'),[
				'json'=>$payloads
			]);
			return $this->getResponse(json_decode($response->getBody()));
		} catch (\Exception $e) {
			throw $e;
		}
	}

	public function paymentType(array $items, float $amount)
	{
		try {
			$payloads = [
				'server_key'=> $this->getServerKey(),
				'amount' => $amount,
				'items'=> $items
			];
			$response = $this->request()->post($this->getRelativeUrl('payments'),[
				'json'=>$payloads
			]);
			return $this->getResponse(json_decode($response->getBody()));
		} catch (\Exception $e) {
			throw $e;
		}
	}

	public function check(string $transaction_id, string $signature_key)
	{
		try {
			$payloads = [
				'transaction_id'=>$transaction_id,
				'signature_key'=>$signature_key
			];
			$response = $this->request()->get($this->getRelativeUrl('update'),[
				'query'=>$payloads
			]);
			return $this->getResponse(json_decode($response->getBody()));
		} catch (\Exception $e) {
			throw $e;
		}
	}

	public function cancel(string $order_id,string $transaction_id,string $cancellation_reason, string $cancelled_by, string $cancellation_date)
	{
		try {
			$payloads = [
				'server_key'=>$this->getServerKey(),
				'order_id'=>$order_id,
				'transaction_id'=>$transaction_id,
				'cancellation_reason'=>$cancellation_reason,
				'cancelled_by'=>$cancelled_by,
				'cancellation_date'=>$cancellation_date
			];
			$response = $this->request()->post($this->getRelativeUrl('cancel_transaction'),[
				'form_params'=>$payloads
			]);
			return $this->getResponse(json_decode($response->getBody()));
		} catch (\Exception $e) {
			throw $e;
		}
	}

}