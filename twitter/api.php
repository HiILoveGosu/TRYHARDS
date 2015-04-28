<?php
require "twitteroauth/autoload.php";
require "COSFilter.php";
use Abraham\TwitterOAuth\TwitterOAuth;

$access_token = "3216494091-dMxMpkulQ45xYYvfmwTp63r8hUrjLfbBNTBmNNw";
$secret_token = "v0peamGku6qdHVpzHBcMZtEaHBg0PvB6NjmqYpZMYsp5a";
$consumer_key = "WHpBzn8G0mpdaTN8P26ywGL4x";
$consumer_secret = "mA60R6jAsA5cfyTBqlAEsQ5q4IQUbZYW3d4EGLL8VKAKjmSGB9";

$connection = new TwitterOAuth($consumer_key, $consumer_secret, $access_token, $secret_token);
$content = $connection->get("account/verify_credentials");
$lat = $_GET['lat'];
$long = $_GET['long'];
$radius = $_GET['radius'];
$geocode = (string)$lat . "," . (string)$long . "," . (string)$radius;
$statuses = $connection->get("search/tweets", array("q" => "", "geocode" => $geocode, "result_type" => "recent", "until" => "2015-04-29"));
/*
print("<pre>");
print_r($statuses);
print("</pre>");*/

$filters = array (
    "statuses" => array(
        array(
            "created_at",
            "text",
            "user" => array (
                "name",
                "screen_name"
            ),
            "geo" => array (
                "coordinates" => array(
                )
            )
        )
    )

);
$filter = new COSFilter(json_decode(json_encode($statuses), true), $filters);

print json_encode($statuses);
