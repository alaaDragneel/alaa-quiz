<?php
use App\Http\Controllers\BotManController;
use BotMan\BotMan\BotMan;
use App\Http\Conversations\QuizConversation;
use App\Http\Conversations\HighscoreConversation;
use App\Http\Conversations\WelcomeConversation;
use App\Http\Conversations\PrivacyConversation;
use App\Http\Middleware\TypingMiddleware;

$botman = resolve('botman');

$typingMiddleware = new TypingMiddleware();
$botman->middleware->sending($typingMiddleware);

$botman->hears('Hi', function ($bot) {
    $bot->reply('Hello!');
});

$botman->hears('It just works', function ($bot) {
    $bot->reply('Yep 🤘');
}); 

$botman->hears('start', function (BotMan $bot) {
    $bot->startConversation(new QuizConversation());
});

$botman->hears('/delete|delete', function (BotMan $bot) {
    $bot->startConversation(new PrivacyConversation());
});

// For The Users That Register For The First Time
$botman->hears('/start', function (BotMan $bot) {
    $bot->startConversation(new WelcomeConversation());
})->stopsConversation();

$botman->hears('/about|about', function (BotMan $bot) {
    $bot->reply('AlaaQuiz is a project by Mohamed Alaa El-Din.');
})->stopsConversation();

$botman->hears('/highscore|highscore', function (BotMan $bot) {
    $bot->startConversation(new HighscoreConversation());
})->stopsConversation();



// $botman->hears('Start conversation', BotManController::class.'@startConversation');
