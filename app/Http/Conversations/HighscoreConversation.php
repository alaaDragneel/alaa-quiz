<?php

namespace App\Http\Conversations;

use BotMan\BotMan\Messages\Conversations\Conversation;
use App\Highscore;

class HighscoreConversation extends Conversation
{
    /**
     * Start the conversation.
     *
     * @return mixed
     */
    public function run()
    {
        $this->showHighscore();
    }

    private function showHighscore()
    {
        $topUsers = Highscore::topUsers();

        if (!$topUsers->count()) {
            return $this->say('The highscore is still empty. Be the first one! 👍');
        }

        $topUsers->transform(function ($user) {
            return "{$user->rank} - {$user->name} {$user->points} points";
        });

        $this->say('Here is the current highscore. Do you think you can do better? Start the quiz: /Start.');
        $this->say('🏆 HIGHSCORE 🏆');
        $this->say($topUsers->implode("\n"));
    }
}
