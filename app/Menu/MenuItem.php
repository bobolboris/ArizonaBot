<?php

namespace App\Menu;

use App\User;
use Telegram\Bot\Keyboard\Keyboard;
use Telegram\Bot\Objects\Update;

abstract class MenuItem
{
    protected $name;
    protected $code = -1;
    protected $codeBack = -1;
    protected $menuKernel = null;

    public abstract function action(User $user);

    public abstract function handleAction(Update $update, User $user): bool;

    public abstract function handleBack(Update $update, User $user): bool;

    protected function getKeyboardArray(): array
    {
        return [[$this->menuKernel->getBackText()], [$this->menuKernel->getMainMenuText()]];
    }

    public function getKeyboard()
    {
        return Keyboard::make([
            'keyboard' => $this->getKeyboardArray(),
            'resize_keyboard' => true,
            'one_time_keyboard' => false
        ]);
    }

    public function getCode(): int
    {
        return $this->code;
    }

    public function setCode(int $code)
    {
        $this->code = $code;
    }

    public function getCodeBack(): int
    {
        return $this->codeBack;
    }

    public function setCodeBack(int $codeBack)
    {
        $this->codeBack = $codeBack;
    }

    public function __construct(MenuKernel $menuKernel)
    {
        $this->menuKernel = $menuKernel;
    }
}