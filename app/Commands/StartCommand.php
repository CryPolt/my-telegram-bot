<?php

namespace App\Commands;
use Telegram\bot\Commands\Command;

class StartCommand extends Command{
    protected string $name ='start';
    protected string $description = 'Запуск / Перезапуск бота';

    public function handle(){
        $message = 'TEXT';
        $this->replyWithMessage([
            'text' => $message,
            'parse_mode' => 'HTML'
        ]);
    }
}
