<?php

namespace App\Mail\Telegram;

use Stringable;

abstract class Notification implements Stringable
{
    protected string $content;
    protected array $buttons;


    public function __toString(): string
    {
        return $this->getContent();
    }

    public function getContent(): string
    {
        return $this->content;
    }

    /**
     * @param string $content
     * @return $this
     */

    public function setContent(string $content): static
    {
        $this->content = $content;
        return $this;
    }

    public function getButtons(): array
    {
        return $this->buttons;
    }

    public function setButtons(array $buttons): static
    {
        $this->buttons = $buttons;
        return $this;
    }
}
