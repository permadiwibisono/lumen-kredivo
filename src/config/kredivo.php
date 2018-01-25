<?php
return[
	'redirect_success' => env('KREDIVO_SUCCESS_URI',''),
	'cancel_uri' => env('KREDIVO_MERCHANT_CANCEL_URI',''),
	'push_uri' => env('KREDIVO_PUSH_URI',''),
	'development_key' => env('KREDIVO_SANDBOX_KEY',''),	
	'server_key' => env('KREDIVO_SERVER_KEY',''),
	'version' => env('KREDIVO_VERSION','v2'),
	'production' => env('KREDIVO_PRODUCTION',false)
];