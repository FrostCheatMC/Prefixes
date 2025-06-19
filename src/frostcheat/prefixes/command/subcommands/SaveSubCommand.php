<?php

namespace frostcheat\prefixes\command\subcommands;

use CortexPE\Commando\BaseSubCommand;

use frostcheat\prefixes\Prefixes;

use pocketmine\command\CommandSender;
use pocketmine\utils\TextFormat;

class SaveSubCommand extends BaseSubCommand
{

    public function __construct()
    {
        parent::__construct("save", "Save the prefixes and sessions");
        $this->setPermission("prefixes.command.save");
    }

    /**
     * @inheritDoc
     */
    protected function prepare(): void
    {
        // TODO: Implement prepare() method.
    }

    public function onRun(CommandSender $sender, string $aliasUsed, array $args): void
    {
        Prefixes::getInstance()->getProvider()->save();
        $sender->sendMessage(TextFormat::colorize(str_replace("%plugin-prefix%", Prefixes::getInstance()->getProvider()->getMessages()->get("plugin-prefix"), Prefixes::getInstance()->getProvider()->getMessages()->get("plugin-save-message"))));
    }
}