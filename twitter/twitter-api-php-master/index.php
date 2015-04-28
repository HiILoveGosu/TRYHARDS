<?php

/*
if ( strrpos($http_origin, "mysite1.net") || strrpos($http_origin, "mysite2") ){
    header("Access-Control-Allow-Origin: $http_origin");
}
*/
header('Content-Type: application/json');

ini_set('display_errors', 1);
require_once('TwitterAPIExchange.php');

/** Set access tokens here - see: https://dev.twitter.com/apps/ **/
$settings = array(
    'oauth_access_token' => "3216494091-dMxMpkulQ45xYYvfmwTp63r8hUrjLfbBNTBmNNw",
    'oauth_access_token_secret' => "v0peamGku6qdHVpzHBcMZtEaHBg0PvB6NjmqYpZMYsp5a",
    'consumer_key' => "WHpBzn8G0mpdaTN8P26ywGL4x",
    'consumer_secret' => "mA60R6jAsA5cfyTBqlAEsQ5q4IQUbZYW3d4EGLL8VKAKjmSGB9"
);


/** Perform a GET request and echo the response **/
/** Note: Set the GET field BEFORE calling buildOauth(); **/

$url = 'https://api.twitter.com/1.1/search/tweets.json';
$getfield = '?'.$_SERVER['QUERY_STRING'];
$requestMethod = 'GET';
$twitter = new TwitterAPIExchange($settings);

$api_response = $twitter ->setGetfield($getfield)
    ->buildOauth($url, $requestMethod)
    ->performRequest();

echo $api_response;