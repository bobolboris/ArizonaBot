<?php

namespace App\Menu\MenuItems;

use App\Facade\Keeper;
use App\Menu\MenuItem;
use App\Parsers\ParserRating;
use App\RatingItem;
use App\User;
use Illuminate\Support\Facades\Log;
use Telegram\Bot\Laravel\Facades\Telegram;
use Telegram\Bot\Objects\Update;

class ServerRankingSelectItem extends MenuItem
{
    protected $code = 4;
    protected $ri = null;


    public function handleBack(Update $update, User $user): bool
    {
        throw new \Exception('ServerRankingSelectItem: операция back не поддерживается');
    }

    public function action(User $user)
    {
        if ($this->ri == null) {
            throw new \Exception('ServerRankingSelectItem: операция action не поддерживается');
        }
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
        $parser = new ParserRating();

        $arr = $parser->parse(sprintf('/rating/%s/%s', $this->ri->shortcut, $serverNumber));
        if (count($arr) == 0) {
            throw new \Exception('ServerRankingSelectItem: данные рейтингов почему-то отсутствуют');
        }
        $format = ($this->ri->shortcut == 'old') ? "%s) %s\n" : "%s) %s %s\n";
        $response = '';
        foreach ($arr as $value) {
            $response .= sprintf($format, $value[0], $value[1], $value[2]);
        }
        Telegram::sendMessage([
            'chat_id' => $user->chat_id,
            'text' => $response
        ]);
    }

    public function handleAction(Update $update, User $user): bool
    {
        $text = trim($update->getMessage()->text);
        $ratingItems = RatingItem::all();
        foreach ($ratingItems as $value) {
            if ($value->name == $text) {
                $this->ri = $value;
                $this->action($user);
                return true;
            }
        }
        return false;
    }


}