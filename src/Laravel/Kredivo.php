<?php 
namespace Pewe\Kredivo\Laravel;
use \GuzzleHttp\Client as Client;
use Pewe\Kredivo\Kredivo as KredivoClient;

/**
* Kredivo wrapper for laravel or lumen app.
*/
class Kredivo
{
	private $client;
	function __construct()
	{
		$this->client = new KredivoClient([
			'is_production' => config('kredivo.production', false),
			'version' => config('kredivo.version', 'v2'),
			'server_key' => config('kredivo.production', false) ? config('kredivo.server_key'):config('kredivo.development_key'),
			'push_uri' => config('kredivo.push_uri'),
			'user_cancel_uri' => config('kredivo.cancel_uri'),
			'back_to_store_uri' => config('kredivo.settlement_uri')
		]);
	}

	public function checkout(array $payloads)
	{
		$response = $this->client->checkout($payloads);
		return collect($response);
	}

	public function paymentType(array $items, float $amount)
	{
		$response = $this->client->paymentType($items, $amount);
		return collect($response);
	}

	public function check(string $transaction_id, string $signature_key)
	{
		$response = $this->client->check($transaction_id, $signature_key);
		return collect(json_decode($response->getBody()));
	}

	public function cancel(string $order_id,string $transaction_id,string $cancellation_reason, string $cancelled_by, string $cancellation_date)
	{
		$response = $this->client->cancel($order_id, $transaction_id, $cancellation_reason, $cancelled_by, $cancellation_date);
		return collect($response);
	}

}