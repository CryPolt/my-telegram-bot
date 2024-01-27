<?php

namespace App\Commands;

use App\Models\TelegramUser;
use Telegram\bot\Commands\Command;
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

    public function sendAnswerForOldUsers()
    {
        $this->replyWithMessage([
            'text' => 'Рады видеть вас снова!🥳'
        ]);
    }
    public function sendAnswerForNewUsers()
    {
        $this->replyWithMessage([
            'text' => 'Добро пожаловать в наш телеграм бот!'
        ]);
    }




}
