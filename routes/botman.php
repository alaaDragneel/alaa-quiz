<?php
use App\Http\Controllers\BotManController;
use BotMan\BotMan\BotMan;
use App\Http\Conversations\QuizConversation;

$botman = resolve('botman');

$botman->hears('Hi', function ($bot) {
    $bot->reply('Hello!');
});

$botman->hears('It just works', function ($bot) {
    $bot->reply('Yep 🤘');
}); 

$botman->hears('Start', function (BotMan $bot) {
    $bot->startConversation(new QuizConversation());
});

// $botman->hears('Start conversation', BotManController::class.'@startConversation');
