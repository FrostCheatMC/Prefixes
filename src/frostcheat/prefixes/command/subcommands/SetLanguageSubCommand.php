<?php

namespace frostcheat\prefixes\command\subcommands;

use CortexPE\Commando\BaseSubCommand;

use frostcheat\prefixes\command\args\LanguageArgument;
use frostcheat\prefixes\language\LanguageManager;
use frostcheat\prefixes\Prefixes;

use pocketmine\command\CommandSender;
use pocketmine\utils\TextFormat;

class SetLanguageSubCommand extends BaseSubCommand
{

    public function __construct()
    {
        parent::__construct("setlanguage", "Enter the language you want for your plugin");
        $this->setPermission("prefixes.command.setlanguage");
    }

    /**
     * @inheritDoc
     */
    protected function prepare(): void
    {
        $this->registerArgument(0, new LanguageArgument("language"));
    }

    public function onRun(CommandSender $sender, string $aliasUsed, array $args): void
    {
        $language = $args["language"];

        Prefixes::getInstance()->getConfig()->set("default-language", $language);
        Prefixes::getInstance()->getConfig()->save();
        LanguageManager::getInstance()->setDefaultLanguage($language->getName());
        $sender->sendMessage(TextFormat::colorize(str_replace(["%plugin-prefix%", "%language%", "%default%"], [Prefixes::getInstance()->getProvider()->getMessages()->get("plugin-prefix"), $language->getName(), "true"], Prefixes::getInstance()->getProvider()->getMessages()->get("player-setlanguage-succesfuly"))));
    }
}