<?php

namespace App\Menu;

use App\Exceptions\UserLogicException;
use App\User;
use Illuminate\Support\Facades\Log;
use Telegram\Bot\Laravel\Facades\Telegram;
use Telegram\Bot\Objects\Update;


class MenuKernel
{
    protected $backText = 'Назад';
    protected $mainMenuText = 'В главное меню';
    protected $menus = [];
    protected $toMainMenuHandler = null;

    public function __construct()
    {
        $this->menus = [
            'StartItem' => new MenuItems\StartItem($this),
            'MainItem' => new MenuItems\MainItem($this),
            'StartGameMenu' => new MenuItems\StartGameMenu($this),
            'ServersListItem' => new MenuItems\ServersListItem($this),
            'ServerSelectItem' => new MenuItems\ServerSelectItem($this),
            'ServerRankingsListItem' => new MenuItems\ServerRankingsListItem($this),
            'ServerRankingSelectItem' => new MenuItems\ServerRankingSelectItem($this),
            'MapStateItem' => new MenuItems\MapStateItem($this),
        ];
        $this->toMainMenuHandler = $this->menus['StartItem'];
    }

    public function get($key)
    {
        return $this->menus[$key];
    }

    public function getOrFail($key)
    {
        $obj = $this->get($key);
        if ($obj == null) {
            throw new \Exception("Объект $key не найдет");
        }
        return $obj;
    }

    public function throwException(\Exception $e, $user)
    {
        if ($user == null) {
            Log::error('Не удалось бросить исключение по причине того что пользователь null');
            return;
        }
        Telegram::sendMessage([
            'chat_id' => $user->chat_id,
            'text' => $e->getMessage()
        ]);
    }

    public function getUser($chat_id)
    {
        $user = User::where('chat_id', $chat_id)->first();
        if ($user != null) {
            if ($user->state_id == null) {
                $user->state_id = 0;
                $user->save();
            }
            $user->state;
            if ($user->substate_id != null) {
                $user->substate;
            }
            return $user;
        }
        $user = new User(['chat_id' => $chat_id, 'state_id' => 0]);
        $user->save();
        $user->state;
        return $user;
    }

    public function handle(array $updates)
    {
        foreach ($updates as $update) {
            $this->handleUpdate($update);
        }
    }

    protected function handleUpdate(Update $update)
    {
        $user = $this->getUser($update->getChat()->id);
        try {
            $text = trim($update->getMessage()->text);

            if ($text == $this->backText) {
                $state = $this->handleBack($update, $user);
            } else {
                $state =
                    ($text == $this->mainMenuText) ? $this->toMainMenu($user) : $this->handleAction($update, $user);
            }

            if (!$state) {
                throw new UserLogicException('Неизвестная команда');
            }
        } catch (UserLogicException $e) {
            $this->throwException($e, $user);
        } catch (\Exception $e) {
            $this->throwException(new \Exception('Что-то пошло не так...'), $user);
            Log::error($e->getMessage());
        }
    }

    protected function handleAction(Update $update, User $user): bool
    {
        foreach ($this->menus as $menu) {
            if ($menu->getCode() == $user->state->code || $menu->getCode() == -1) {
                if ($menu->handleAction($update, $user)) {
                    return true;
                }

            }
        }
        return false;
    }

    protected function handleBack(Update $update, User $user): bool
    {
        foreach ($this->menus as $menu) {
            if ($menu->getCodeBack() == $user->state->code) {
                if ($menu->handleBack($update, $user)) {
                    return true;
                }

            }
        }
        return false;
    }

    public function toMainMenu(User $user): bool
    {
        $this->toMainMenuHandler->action($user);
        return true;
    }

    public function getBackText(): string
    {
        return $this->backText;
    }

    public function setBackText(string $backText)
    {
        $this->backText = $backText;
    }

    public function getMainMenuText(): string
    {
        return $this->mainMenuText;
    }

    public function setMainMenuText(string $mainMenuText)
    {
        $this->mainMenuText = $mainMenuText;
    }


}