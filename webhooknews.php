<?php

$input = json_decode(file_get_contents('php://input'), true);
$chat_id = $input['message']['chat']['id'];
$text = $input['message']['text'];
$botToken = "7885179301:AAGvD2W69anYqI4IryDB6iE_L7-UvK_RJis";
$url = "https://api.telegram.org/bot{$botToken}/sendMessage";


$newsapi= "k049hb3PZkWeJrCnYo0KGa89dlWnKy8W255v3ijw";

// Third-party News API URL
$news_api_url = "https://api.thenewsapi.com/v1/news/top?api_token=$newsapi&locale=us";


// Get the latest news
$response = file_get_contents($news_api_url);
$news_data = json_decode($response, true);


$message = '';

if (strtolower($text) == "hello" || strtolower($text) == "hi" || strtolower($text) == "hey") {
    $message = "Hi 😊, How may i help you? Want the latest top news? \nEnter yes/no or /start to proceed.";
} elseif (strtolower($text) == "/start" || strtolower($text) == "yes") {
    if ($news_data['data']) { 
        // Get the first news article
        for ($i = 0; $i < 3; $i++) {

            $latest_article_title = $news_data["data"][$i]['title'];
            $latest_article_description = $news_data["data"][$i]['description'];
            $article_url = $news_data["data"][$i]["source"];

            // Sending the latest news to the user
            $message = $message . "News " . $i + 1 . ":\nTitle: $latest_article_title. \nDescription: $latest_article_description \nIf you want to read more visit:  $article_url \n\n\n";
        }
    } else {
        $message = "Sorry, I can't get the latest news at the moment. \n Try again later.";
    }
} elseif (strtolower($text) == 'no') {
    $message = "Bye bye!👍 \nI'm always a chat away, just type /start or yes when you are ready and i will get you the latest news.";
} else {
    $message = "I don't understand what you mean. \n type /start or yes to get started!";
}



$data = [
    'chat_id' => $chat_id,
    'text' => $message
];

file_get_contents($url . '?' . http_build_query($data));


?>
