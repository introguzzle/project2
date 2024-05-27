<?php

namespace App\Services\Telegram\Commands;

use App\Services\Telegram\BotCommand;

class CommandStrategyResolver
{
    protected BotCommand $botCommand;
    protected string $context;

    /**
     * @param BotCommand $botCommand
     * @param string $context
     */
    public function __construct(BotCommand $botCommand, string $context)
    {
        $this->botCommand = $botCommand;
        $this->context = $context;
    }

    public function getName(): string
    {
        return substr(
            $this->context,
            $this->botCommand->getOffset(),
            $this->botCommand->getLength()
        );
    }

    public function resolve(): CommandStrategy
    {
        return match ($this->getName()) {
            '/auth'     => new AuthCommandStrategy($this->botCommand),
            '/logout'   => new LogoutCommandStrategy($this->botCommand),
            '/set_name' => new SetNameCommandStrategy($this->botCommand),
            default     => new DefaultCommandStrategy()
        };
    }
}
