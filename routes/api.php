<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/create-email', function(Request $request){

  $endpoint = env("MIAB_HOST").'admin/mail/aliases/add';
$client = new \GuzzleHttp\Client();
$address = Str::random(8)."@".env('EMAIL_DOMAIN');
$forward = $request->forward_to;

$response = $client->request('POST', $endpoint, [
  'query' => [
    'address' => $address,
    'forwards_to' => $forward,
  ],
  'auth' => [
    'username'     => env('MIAB_EMAIL'),
    'password' => env('MIAB_API_KEY')
    ]
]);

// url will be: http://my.domain.com/test.php?key1=5&key2=ABC;

$statusCode = $response->getStatusCode();
$content = $response->getBody();

// or when your server returns json
 //$content = json_decode($response->getBody(), true);
 return $content;
});
