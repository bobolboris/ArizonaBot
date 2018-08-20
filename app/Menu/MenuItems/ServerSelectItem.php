<?php

namespace App\Menu\MenuItems;

use App\Facade\Keeper;
use App\Menu\MenuItem;
use App\Server;
use App\User;
use Telegram\Bot\Keyboard\Keyboard;
use Telegram\Bot\Laravel\Facades\Telegram;
use Telegram\Bot\Objects\Update;

class ServerSelectItem extends MenuItem
{
    protected $code = 2;
    protected $codeBack = 3;


    protected function getKeyboardArray(): array
    {
        $keyboard = parent::getKeyboardArray();
        $keyboard[] = ['Карта штата'];
        $keyboard[] = ['Рейтинги сервера'];
        return $keyboard;
    }

    public function handleAction(Update $update, User $user): bool
    {
        $text = trim($update->getMessage()->text);
        $server = Server::where('name', $text)->first();

        if ($server != null) {
            Keeper::set($user->id, 3, 'serverNumber', $server->number);
            $this->action($user);
            return true;
        }

        return false;
    }

    public function action(User $user)
    {
        $user->setState(3)->save();
        Telegram::sendMessage([
            'chat_id' => $user->chat_id,
            'text' => 'Выберите пункт:',
            'reply_markup' => $this->getKeyboard()
        ]);
    }

    public function handleBack(Update $update, User $user): bool
    {
        $this->menuKernel->getOrFail('ServersListItem')->action($user);
        return true;
    }
}