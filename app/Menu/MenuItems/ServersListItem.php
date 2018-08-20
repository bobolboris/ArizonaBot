<?php

namespace app\Menu\MenuItems;

use App\Menu\MenuItem;
use App\Server;
use App\User;
use Telegram\Bot\Keyboard\Keyboard;
use Telegram\Bot\Laravel\Facades\Telegram;
use Telegram\Bot\Objects\Update;

class ServersListItem extends MenuItem
{
    protected $name = 'Серверы';
    protected $code = 1;
    protected $codeBack = 2;

    protected function getKeyboardArray(): array
    {
        $keyboard = parent::getKeyboardArray();
        $servers = Server::all();
        foreach ($servers as $server) {
            $keyboard[] = [$server->name];
        }
        return $keyboard;
    }

    public function action(User $user)
    {
        $user->setState(2)->save();
        Telegram::sendMessage([
            'chat_id' => $user->chat_id,
            'text' => 'Выберите сервер:',
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
        $this->menuKernel->getOrFail('StartItem')->action($user);
        return true;
    }

}