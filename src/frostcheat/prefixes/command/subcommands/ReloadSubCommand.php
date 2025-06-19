<?php

namespace frostcheat\prefixes\command\subcommands;

use CortexPE\Commando\BaseSubCommand;

use frostcheat\prefixes\Prefixes;

use pocketmine\command\CommandSender;
use pocketmine\utils\TextFormat;

class ReloadSubCommand extends BaseSubCommand
{
    public function __construct()
    {
        parent::__construct("reload", "Reload all plugin");
        $this->setPermission("prefixes.command.reload");
    }

    /**
     * @inheritDoc
     */
    protected function prepare(): void {}

    public function onRun(CommandSender $sender, string $aliasUsed, array $args): void
    {
        Prefixes::getInstance()->getProvider()->reload();
        $sender->sendMessage(TextFormat::colorize(str_replace("%plugin-prefix%", Prefixes::getInstance()->getProvider()->getMessages()->get("plugin-prefix"), Prefixes::getInstance()->getProvider()->getMessages()->get("plugin-reload-message"))));
    }
}