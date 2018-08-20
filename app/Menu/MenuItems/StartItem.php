<?php

namespace App\Menu\MenuItems;

use App\Menu\MenuItem;
use App\User;
use Telegram\Bot\Keyboard\Keyboard;
use Telegram\Bot\Laravel\Facades\Telegram;
use Telegram\Bot\Objects\Update;

class StartItem extends MenuItem
{
    protected $name = '/start';
    protected $code = 0;

    public function handleBack(Update $update, User $user): bool
    {
        throw new \Exception('StartItem: операция back не поддерживается');
    }

    protected function getKeyboardArray(): array
    {
        return [['Главная'], ['Как начать играть?'], ['Серверы']];
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
        $user->setState(1)->save();
        Telegram::sendMessage([
            'chat_id' => $user->chat_id,
            'text' => 'Выберите пункт:',
            'reply_markup' => $this->getKeyboard()
        ]);
    }
}