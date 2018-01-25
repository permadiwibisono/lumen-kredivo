<?php 
namespace Pewe\Kredivo;
use GuzzleHttp\Exception\RequestException;
use \GuzzleHttp\Client as Client;

/**
* 
*/
class Kredivo
{
	private static $SANDBOX_URL ='https://sandbox.kredivo.com';
	private static $API_URL ='https://api.kredivo.com';
	private static $VERSION;
	function __construct()
	{
		Kredivo::$VERSION = config('kredivo.version','v2');
	}

	private function getServerKey()
	{
		return config('kredivo.production',false)? config('kredivo.server_key'):config('kredivo.development_key');
	}

	private function getApiUrl()
	{
		return (config('kredivo.production',false)? Kredivo::$API_URL : Kredivo::$SANDBOX_URL);
	}

	private function getVersion()
	{
		return Kredivo::$VERSION;
	}

	public function getRelativeUrl($route)
	{
		return 'kredivo/'.Kredivo::$VERSION.'/'.$route;
	}

	public function checkout(array $payloads)
	{
		// echo $this->getApiUrl();
		$payloads['server_key'] = $this->getServerKey();
		$payloads['push_uri'] = config('kredivo.push_uri');
		$payloads['back_to_store_uri'] = config('kredivo.cancel_uri');
		$client=new Client([
			'base_uri'=>$this->getApiUrl(),
			'headers'=> [
				'Content-Type' =>	'application/json',
				'Accept' =>	'application/json'
			]
		]);
		$response=$client->post($this->getRelativeUrl('checkout_url'),[
			'json'=>$payloads
		]);
		return collect(json_decode($response->getBody()));
	}

	public function check(string $transaction_id, string $signature_key)
	{
		$client=new Client([
			'base_uri'=>$this->getApiUrl(),
			'headers'=> [
				'Content-Type' =>	'application/json',
				'Accept' =>	'application/json'
			]
		]);
		$payloads = [
			'server_key'=>$this->getServerKey(),
			'transaction_id'=>$transaction_id,
			'signature_key'=>$signature_key
		];
		$response=$client->get($this->getRelativeUrl('check'),[
			'query'=>$payloads
		]);
		return collect(json_decode($response->getBody()));
	}

	public function cancel(string $order_id,string $transaction_id,string $cancellation_reason, string $cancelled_by, string $cancellation_date)
	{
		$client=new Client([
			'base_uri'=>$this->getApiUrl(),
			'headers'=> [
				'Content-Type' =>	'application/json',
				'Accept' =>	'application/json'
			]
		]);
		$payloads = [
			'server_key'=>$this->getServerKey(),
			'order_id'=>$order_id,
			'transaction_id'=>$transaction_id,
			'cancellation_reason'=>$reason,
			'cancelled_by'=>$cancelled_by,
			'cancellation_date'=>$cancellation_date
		];
		$response=$client->post($this->getRelativeUrl('cancel_transaction'),[
			'form_params'=>$payloads
		]);
		return collect(json_decode($response->getBody()));
	}

}