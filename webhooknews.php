<?php

file_put_contents("telegram_log.txt", date('Y-m-d H:i:s') . "\n" . file_get_contents("php://input") . "\n\n", FILE_APPEND);

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$input = json_decode(file_get_contents('php://input'), true);

if (!isset($input['message'])) {
    exit(); // Ignore other types of updates
}

$chat_id = $input['message']['chat']['id'] ?? null;
$text = strtolower($input['message']['text'] ?? '');

$botToken = "7885179301:AAGvD2W69anYqI4IryDB6iE_L7-UvK_RJis";
$url = "https://api.telegram.org/bot{$botToken}/sendMessage";

$newsapi = "k049hb3PZkWeJrCnYo0KGa89dlWnKy8W255v3ijw";
$news_api_url = "https://api.thenewsapi.com/v1/news/top?api_token=$newsapi&locale=us";

$response = @file_get_contents($news_api_url);
$news_data = json_decode($response, true);

$message = '';

if (in_array($text, ['hi', 'hello', 'hey'])) {
    $message = "Hi 😊, How may I help you? Want the latest top news?\nEnter yes/no or /start to proceed.";
} elseif (in_array($text, ['/start', 'yes'])) {
    if (!empty($news_data['data'])) {
        for ($i = 0; $i < 3; $i++) {
            $article = $news_data['data'][$i];
            $title = $article['title'] ?? 'No title';
            $desc = $article['description'] ?? 'No description';
            $url = $article['url'] ?? 'No link';

            $message .= "📰 News " . ($i + 1) . ":\n";
            $message .= "Title: $title\nDescription: $desc\nRead more: $url\n\n";
        }
    } else {
        $message = "Sorry, I can't get the latest news at the moment. Please try again later.";
    }
} elseif ($text == 'no') {
    $message = "Bye bye! 👍 I'm always a chat away. Type /start or yes when you're ready!";
} else {
    $message = "I didn't understand that. Please type /start or yes to get the latest news.";
}

$data = [
    'chat_id' => $chat_id,
    'text' => $message
];

file_get_contents("https://api.telegram.org/bot{$botToken}/sendMessage?" . http_build_query($data));
