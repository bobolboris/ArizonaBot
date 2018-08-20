<?php

namespace App\Http\Controllers;

use App\Menu\MenuKernel;
use Illuminate\Http\Request;
use Telegram\Bot\Laravel\Facades\Telegram;

class MainController extends Controller
{
    public function getUpdateAction()
    {
        $mk = new MenuKernel();
        $updates = Telegram::getUpdates();
        $mk->handle($updates);
        if (count($updates) > 0) {
            Telegram::getUpdates(['offset' => $updates[count($updates) - 1]->update_id + 1]);
        }
        return 'ok';
    }
}
