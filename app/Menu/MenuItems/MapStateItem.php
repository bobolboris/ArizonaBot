<?php

namespace app\Menu\MenuItems;


use App\Facade\Keeper;
use App\Menu\MenuItem;
use App\User;
use App\Http\Controllers\MapGenerator;
use Illuminate\Support\Facades\Log;
use Telegram\Bot\FileUpload\InputFile;
use Telegram\Bot\Laravel\Facades\Telegram;
use Telegram\Bot\Objects\Update;

class MapStateItem extends MenuItem
{
    protected $code = 3;
    protected $name = 'Карта штата';

    public function action(User $user)
    {
        $serverNumber = Keeper::get($user->id, 3, 'serverNumber');
        if ($serverNumber == null) {
            Log::error('Пользователь каким-то образом выбрал меню рейтинга сервера, без выбора сервера');
            Telegram::sendMessage([
                'chat_id' => $user->chat_id,
                'text' => 'С начало выберите сервер'
            ]);
            $this->menuKernel->getOrFail('ServersListItem')->action($user);
            return;
        }
        $mp = new MapGenerator();
        $mp->generate($serverNumber);
        Telegram::sendPhoto([
            'chat_id' => $user->chat_id,
            'photo' => new InputFile(public_path("storage/map/maps/${serverNumber}.jpg"), $serverNumber . '.jpg')
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
        throw new \Exception('MapStateItem: операция back не поддерживается');
    }
}