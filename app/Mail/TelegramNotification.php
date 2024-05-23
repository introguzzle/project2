<?php

namespace App\Mail;

use Stringable;

abstract class TelegramNotification implements Stringable
{
    protected string $content;
    protected array $buttons;


    public function __toString(): string
    {
        return $this->content;
    }

    public function getContent(): string
    {
        return $this->content;
    }

    public function setContent(string $content): TelegramNotification
    {
        $this->content = $content;
        return $this;
    }

    public function getButtons(): array
    {
        return $this->buttons;
    }

    public function setButtons(array $buttons): TelegramNotification
    {
        $this->buttons = $buttons;
        return $this;
    }
}
