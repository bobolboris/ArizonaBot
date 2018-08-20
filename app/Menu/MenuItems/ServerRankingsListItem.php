<?php

namespace App\Menu\MenuItems;


use App\Menu\MenuItem;
use App\RatingItem;
use App\User;
use Telegram\Bot\Keyboard\Keyboard;
use Telegram\Bot\Laravel\Facades\Telegram;
use Telegram\Bot\Objects\Update;

class ServerRankingsListItem extends MenuItem
{
    protected $name = 'Рейтинги сервера';
    protected $code = 3;
    protected $codeBack = 4;

    public function getKeyboard()
    {
        $keyboard = [];
        $ri = RatingItem::all();
        foreach ($ri as $value) {
            $keyboard[] = [$value->name];
        }
        $keyboard[] = [$this->menuKernel->getBackText()];
        return Keyboard::make(['keyboard' => $keyboard, 'resize_keyboard' => true, 'one_time_keyboard' => false]);
    }

    public function action(User $user)
    {
        $user->setState(4)->save();
        Telegram::sendMessage([
            'chat_id' => $user->chat_id,
            'text' => 'Выберите пункт:',
            'reply_markup' => $this->getKeyboard()
        ]);
    }

    public function handleAction(Update $update, User $user): bool
    {
        if (trim($update->getMessage()->text) == $this->name) {
            $this->action($user);
            return true;
        }
        return false;
    }

    public function handleBack(Update $update, User $user): bool
    {
        $this->menuKernel->getOrFail('ServerSelectItem')->action($user);
        return true;
    }
}