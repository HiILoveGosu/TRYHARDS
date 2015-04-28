/**
 * Created by Wouter on 28-4-2015.
 */
//Callback functions
var error = function (err, response, body) {
    console.log('ERROR [%s]', err);
};
var success = function (data) {
    console.log('Data [%s]', data);
};

var Twitter = require('twitter-js-client').Twitter;

//Get this data from your twitter apps dashboard
var config = {
    "consumerKey": "WHpBzn8G0mpdaTN8P26ywGL4x",
    "consumerSecret": "mA60R6jAsA5cfyTBqlAEsQ5q4IQUbZYW3d4EGLL8VKAKjmSGB9",
    "accessToken": "3216494091-dMxMpkulQ45xYYvfmwTp63r8hUrjLfbBNTBmNNw",
    "accessTokenSecret": "v0peamGku6qdHVpzHBcMZtEaHBg0PvB6NjmqYpZMYsp5a",
    "callBackUrl": "http://twitter.com"
}

var twitter = new Twitter(config);

//Example calls
/*
twitter.getUserTimeline({ screen_name: 'BoyCook', count: '10'}, error, success);

twitter.getMentionsTimeline({ count: '10'}, error, success);

twitter.getHomeTimeline({ count: '10'}, error, success);

twitter.getReTweetsOfMe({ count: '10'}, error, success);

twitter.getTweet({ id: '1111111111'}, error, success);


//
// Get 10 tweets containing the hashtag haiku
//

twitter.getSearch({'q':'#haiku','count': 10}, error, success);

//
// Get 10 popular tweets with a positive attitude about a movie that is not scary
//

twitter.getSearch({'q':' movie -scary :) since:2013-12-27', 'count': 10, 'result\_type':'popular'}, error, success);
    */
document.write(twitter.getCustomApiCall('/statuses/lookup.json',{ id: '412312323'}, error, success));