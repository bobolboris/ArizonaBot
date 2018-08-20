<?php

namespace App\Menu\MenuItems;


use App\Menu\MenuItem;
use App\User;
use Telegram\Bot\Laravel\Facades\Telegram;
use Telegram\Bot\Objects\Update;

class MainItem extends MenuItem
{
    protected $name = 'Главная';
    protected $code = 1;

    public function handleBack(Update $update, User $user): bool
    {
        throw new \Exception('MainItem: операция back не поддерживается');
    }

    public function handleAction(Update $update, User $user): bool
    {
        if (trim($update->getMessage()->text) == $this->name) {
            $this->action($user);
            return true;
        }
        return false;
    }

    public function action(User $user)
    {
        Telegram::sendMessage([
            'chat_id' => $user->chat_id,
            'parse_mode' => 'HTML',
            'text' => view('Главная')->render()
        ]);
    }
}