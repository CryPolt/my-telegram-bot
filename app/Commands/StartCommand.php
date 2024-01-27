<?php

namespace App\Commands;

use App\Models\TelegramUser;
use Telegram\bot\Commands\Command;
use Telegram\Bot\Keyboard\Keyboard;
use Telegram\Bot\Laravel\Facades\Telegram;
use Telegram\Bot\Objects\User;

class StartCommand extends Command{
    protected string $name ='start';
    protected string $description = 'Запуск / Перезапуск бота';
    protected TelegramUser $telegramUser;


    public function __construct(TelegramUser $telegramUser)
    {
        $this->telegramUser = $telegramUser;
    }


    public function handle(){
        //Получаем всю информацию о пользователе
        $userData = $this->getUpdate()->message->from;
        //Получаем его уникальный ID
        $userId = $userData->id;
        //Пробуем найти юзера в БД
        $telegramUser = $this->telegramUser->where('user_id', '=', $userId)->first();
        //Проверяем, если нашли пользователя отправляем сообщение как старому
        //Иначе добавляем его в бд и отправялем сообщение как новому
        if ($telegramUser) {
            $this->sendAnswerForOldUsers();
            $this->keyboard();
        } else {
            $this->addNewTelegramUser($userData);
            $this->sendAnswerForNewUsers();
        }
    }

    public function addNewTelegramUser(User $userData){
        $this->telegramUser->insert([
            'user_id' => $userData->id,
            'username' => $userData->username,
            'first_name' => $userData->first_name,
            'last_name' => $userData->last_name,
            'language_code' => $userData->language_code,
            'is_premium' => $userData->is_premium,
            'is_bot' => $userData->is_bot,
        ]);

    }

    public function sendAnswerForOldUsers(): void
    {
        $userData = $this->getUpdate()->message->from;
        $userId = $userData->id;
        $telegramUser = $this->telegramUser->where('user_id', '=', $userId)->first();
        $this->replyWithMessage([
            'text' => "Привет {$telegramUser->username}"
        ]);
    }


    public function sendAnswerForNewUsers(): void
    {
        $userData = $this->getUpdate()->message->from;
        $userId = $userData->id;
        $telegramUser = $this->telegramUser->where('user_id', '=', $userId)->first();
        $this->replyWithMessage([
            'text' => "Привет {$telegramUser->username}"
        ]);

    }


    public function keyboard(): void
    {

        if ($this->getUpdate()->message->from->language_code == "en") {
            $this->replyWithMessage([
                'text' => 'Выбери действие',
                'reply_markup' => Keyboard::make()
                    ->inline()->row(array(
                        Keyboard::inlineButton(['text' => 'Перейти на  сайт', 'url' => 'https://bold-amoeba-honest.ngrok-free.app/']),
                        Keyboard::inlineButton(['text' => '/user', 'callback_data' => 'user']),
                    ))]);
        } else {
            $this->replyWithMessage([
                'text' => 'Выбери действие',
                'reply_markup' => Keyboard::make()
                    ->inline()->row(array(
                        Keyboard::inlineButton(['text' => 'Перейти ', 'url' => 'https://bold-amoeba-honest.ngrok-free.app/hook']),
                        Keyboard::inlineButton(['text' => 'ds', 'callback_data' => 'user']),
                    ))]);
        }

    }
}
