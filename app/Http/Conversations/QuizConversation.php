<?php

namespace App\Http\Conversations;

use BotMan\BotMan\Messages\Conversations\Conversation;
use App\Question;
use BotMan\BotMan\Messages\Outgoing\Question as BotManQuestion;
use BotMan\BotMan\Messages\Incoming\Answer as BotManAnswer;
use BotMan\BotMan\Messages\Outgoing\Actions\Button;
use App\Answer;

class QuizConversation extends Conversation
{
    /** @var Question */
    protected $quizQuestions;

    /** @var integer */
    protected $userPoints = 0;

    /** @var integer */
    protected $userCorrectAnswers = 0;

    /** @var integer */
    protected $questionCount = 0;

    /** @var integer */
    protected $currentQuestion = 1;

    /**
     * Start the conversation.
     *
     * @return mixed
     */
    public function run()
    {
        $this->quizQuestions = Question::all()->shuffle();
        $this->questionCount = $this->quizQuestions->count();
        $this->quizQuestions = $this->quizQuestions->keyBy('id');
        $this->showInfo();
    }

    private function showInfo()
    {
        $this->say('You will be shown ' . $this->questionCount . ' questions about Laravel. Every correct answer will reward you with a certain amount of points. Please keep it fair and don\'t use any help. All the best! 🍀');
        $this->checkForNextQuestion();
    }

    private function checkForNextQuestion()
    {
        if ($this->quizQuestions->count()) {
            return $this->askQuestion($this->quizQuestions->first());
        }

        $this->showResult();
    }

    private function askQuestion(Question $question)
    {
        $this->ask($this->createQuestionTemplate($question), function (BotManAnswer $answer) use ($question) {
            $quizAnswer = Answer::find($answer->getValue());

            if (!$quizAnswer) {
                $this->say('Sorry, I did not get that. Please use the buttons.');
                return $this->checkForNextQuestion();
            }

            $this->quizQuestions->forget($question->id);

            if ($quizAnswer->correct_one) {
                $this->userPoints += $question->points;
                $this->userCorrectAnswers++;
                $answerResult = '✅';
            } else {
                $correctAnswer = $question->answers()->where('correct_one', true)->first()->text;
                $answerResult = "❌ (Correct: {$correctAnswer})";
            }
            $this->currentQuestion++;

            $this->say("Your answer: {$quizAnswer->text} {$answerResult}");
            $this->checkForNextQuestion();
        });
    }

    private function createQuestionTemplate(Question $question)
    {
        $questionText = '➡️ Question: ' . $this->currentQuestion . ' / ' . $this->questionCount . ' : ' . $question->text;
        $questionTemplate = BotManQuestion::create($questionText);
        $answers = $question->answers->shuffle();

        foreach ($answers as $answer) {
            $questionTemplate->addButton(Button::create($answer->text)->value($answer->id));
        }

        return $questionTemplate;
    }


    private function showResult()
    {
        $this->say('Finished 🏁');
        $this->say("You made it through all the questions. You reached {$this->userPoints} points! Correct answers: {$this->userCorrectAnswers} / {$this->questionCount}");

    }

}
