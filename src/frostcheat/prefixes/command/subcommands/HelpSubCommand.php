<?php

namespace frostcheat\prefixes\command\subcommands;

use CortexPE\Commando\BaseSubCommand;
use pocketmine\command\CommandSender;
use pocketmine\utils\TextFormat;

class HelpSubCommand extends BaseSubCommand
{
    private array $subCommands = [];
    public function __construct(array $commands)
    {
        parent::__construct("help", "List commands");
        $this->setPermission("prefixes.command.help");
        foreach ($commands as $command) {
            if ($command instanceof BaseSubCommand) {
                $this->subCommands[$command->getName()] = $command->getDescription();
            }
        }
    }

    /**
     * @inheritDoc
     */
    protected function prepare(): void {}

    public function onRun(CommandSender $sender, string $aliasUsed, array $args): void
    {
        $sender->sendMessage(TextFormat::colorize("&bPrefixes List Commands"));
        foreach ($this->subCommands as $name => $description) {
            $sender->sendMessage(TextFormat::colorize("&7/prefix $name - &f$description"));
        }
    }
}