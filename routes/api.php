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

$address = Str::random(8)."@".env('EMAIL_DOMAIN');
$forward = $request->forward_to;

//The URL of the resource that is protected by Basic HTTP Authentication.
$url = env("MIAB_HOST").'admin/mail/aliases/add';

//Your username.
$username = env('MIAB_EMAIL');

//Your password.
$password = env('MIAB_API_KEY');

//Initiate cURL.
$ch = curl_init($url);
$payload = [
  'address' => $address,
  'forwards_to' => $forward,
];
curl_setopt( $ch, CURLOPT_POSTFIELDS, $payload );
//Specify the username and password using the CURLOPT_USERPWD option.
curl_setopt($ch, CURLOPT_USERPWD, $username . ":" . $password);

//Tell cURL to return the output as a string instead
//of dumping it to the browser.
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

//Execute the cURL request.
$response = curl_exec($ch);

//Check for errors.
if(curl_errno($ch)){
    //If an error occured, throw an Exception.
    throw new Exception(curl_error($ch));
}

//Print out the response.
return response()->json([
  'email' => $address
]);
});
