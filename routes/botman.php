<?php
use App\Http\Controllers\BotManController;

$botman = resolve('botman');

$botman->hears('Hi', function ($bot) {
    $bot->reply('Hello!');
});

$botman->hears('It just works', function ($bot) {
    $bot->reply('Yep 🤘');
}); 

$botman->hears('Start conversation', BotManController::class.'@startConversation');
