<?php

namespace App\Commands;

use App\Models\TelegramUser;
use Telegram\bot\Commands\Command;
use Telegram\Bot\Objects\User;

class UserCommand extends Command{
    protected string $name ='user';
    protected string $description = 'User list';
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

    public function sendAnswerForOldUsers(): void
    {
        $users = $this->telegramUser->get();
        $output = "Список пользователей:\n";
        foreach ($users as $user) {
            $output .= "
            {$user->id} ID: {$user->user_id},
            Имя: {$user->first_name},
            Фамилия: {$user->last_name},
            Юзернейм: @{$user->username},
            Selected Language: {$user->selected_language},
            Language: {$user->language_code},
            Is bot: {$user->is_bot},
            Status: {$user->status},
            ";
        }
        $this->replyWithMessage([
            'text' => $output,
        ]);
    }


}
