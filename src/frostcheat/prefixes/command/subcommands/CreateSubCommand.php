<?php

namespace frostcheat\prefixes\command\subcommands;

use CortexPE\Commando\args\RawStringArgument;
use CortexPE\Commando\BaseSubCommand;

use frostcheat\prefixes\Prefixes;

use pocketmine\command\CommandSender;
use pocketmine\utils\TextFormat;

class CreateSubCommand extends BaseSubCommand
{

    public function __construct()
    {
        parent::__construct("create", "Create a prefix");
        $this->setPermission("prefixes.command.create");}

    /**
     * @inheritDoc
     */
    protected function prepare(): void
    {
        $this->registerArgument(0, new RawStringArgument("prefixName"));
        $this->registerArgument(1, new RawStringArgument("format"));
        $this->registerArgument(2, new RawStringArgument("permission"));
    }

    public function onRun(CommandSender $sender, string $aliasUsed, array $args): void
    {
        $prefixName = $args["prefixName"];
        $format = $args["format"];
        $permission = $args["permission"];

        if (Prefixes::getInstance()->getPrefixManager()->getPrefix($prefixName) !== null) {
            $sender->sendMessage(TextFormat::colorize(str_replace("%plugin-prefix%", Prefixes::getInstance()->getProvider()->getMessages()->get("plugin-prefix"), Prefixes::getInstance()->getProvider()->getMessages()->get("prefix-already-exists"))));
            return;
        }

        if (strlen($prefixName) > Prefixes::getInstance()->getConfig()->get("prefix-max-characters-name")) {
            $sender->sendMessage(TextFormat::colorize(str_replace(["%max-characters-name%", "%plugin-prefix%"], [Prefixes::getInstance()->getConfig()->get("prefix-max-characters-name"), Prefixes::getInstance()->getProvider()->getMessages()->get("plugin-prefix")], Prefixes::getInstance()->getProvider()->getMessages()->get('prefix-max-characters-name'))));
            return;
        }

        if (strlen($format) > Prefixes::getInstance()->getConfig()->get("prefix-max-characters-format")) {
            $sender->sendMessage(TextFormat::colorize(str_replace(["%max-characters-format%", "%plugin-prefix%"], [Prefixes::getInstance()->getConfig()->get("prefix-max-characters-format"), Prefixes::getInstance()->getProvider()->getMessages()->get("plugin-prefix")], Prefixes::getInstance()->getProvider()->getMessages()->get('prefix-max-characters-format'))));
            return;
        }

        $checkName = explode(' ', $prefixName);

        if (count($checkName) > 1) {
            $sender->sendMessage(TextFormat::colorize(str_replace("%plugin-prefix%", Prefixes::getInstance()->getProvider()->getMessages()->get("plugin-prefix"), Prefixes::getInstance()->getProvider()->getMessages()->get("prefix-no-spaces-name"))));
            return;
        }

        $checkFormat = explode(' ', $format);

        if (count($checkFormat) > 1) {
            $sender->sendMessage(TextFormat::colorize(str_replace("%plugin-prefix%", Prefixes::getInstance()->getProvider()->getMessages()->get("plugin-prefix"), Prefixes::getInstance()->getProvider()->getMessages()->get("prefix-no-spaces-format"))));
            return;
        }

        Prefixes::getInstance()->getPrefixManager()->createprefix($prefixName, [
            'format' => $format,
            'permission' => $permission
        ]);

        $sender->sendMessage(TextFormat::colorize(str_replace(["%plugin-prefix%", "%prefix-name%"], [Prefixes::getInstance()->getProvider()->getMessages()->get("plugin-prefix"), $prefixName], Prefixes::getInstance()->getProvider()->getMessages()->get('prefix-succesfuly-created'))));
    }
}