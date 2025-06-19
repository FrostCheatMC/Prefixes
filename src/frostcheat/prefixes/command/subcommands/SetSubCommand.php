<?php

namespace frostcheat\prefixes\command\subcommands;

use CortexPE\Commando\args\RawStringArgument;
use CortexPE\Commando\BaseSubCommand;

use frostcheat\prefixes\command\args\PrefixArgument;
use frostcheat\prefixes\prefix\Prefix;
use frostcheat\prefixes\Prefixes;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use pocketmine\Server;
use pocketmine\utils\TextFormat;

class SetSubCommand extends BaseSubCommand
{

    public function __construct()
    {
        parent::__construct("set", "Set prefix to user");
        $this->setPermission("prefixes.command.set");
    }

    /**
     * @inheritDoc
     */
    protected function prepare(): void
    {
        $this->registerArgument(0, new RawStringArgument('playerName'));
        $this->registerArgument(1, new PrefixArgument("prefix"));
    }

    public function onRun(CommandSender $sender, string $aliasUsed, array $args): void
    {
        $prefixName = $args["prefix"];
        $player = Server::getInstance()->getPlayerExact($args["playerName"]);

        if (!($player instanceof Player)) {
            $player = Server::getInstance()->getOfflinePlayer($args["playerName"]);
            if ($player !== null) {
                foreach (Prefixes::getInstance()->getSessionManager()->getSessions() as $uuid => $data) {
                    if (strtolower($player->getName()) === strtolower($data->getName())) {
                        $data->setPrefix($prefixName->getName());
                        $sender->sendMessage(TextFormat::colorize(str_replace(["%prefix-name%", "%player%", "%plugin-prefix%"], [$prefixName->getName(), $player->getName(), Prefixes::getInstance()->getProvider()->getMessages()->get("plugin-prefix")], Prefixes::getInstance()->getProvider()->getMessages()->get("prefix-set-succesfuly"))));
                        return;
                    }
                }
            }
            $sender->sendMessage(TextFormat::colorize(str_replace("%plugin-prefix%", Prefixes::getInstance()->getProvider()->getMessages()->get("plugin-prefix"), Prefixes::getInstance()->getProvider()->getMessages()->get("prefix-set-no-player"))));
            return;
        }

        if (!($prefixName instanceof Prefix)) {
            $sender->sendMessage(TextFormat::colorize(str_replace(["%plugin-prefix%"], [Prefixes::getInstance()->getProvider()->getMessages()->get("plugin-prefix")], Prefixes::getInstance()->getProvider()->getMessages()->get("prefix-set-no-prefix-exists"))));
            return;
        }

        $session = Prefixes::getInstance()->getSessionManager()->getSession($player->getName());
        if ($session !== null) {
            $session->setPrefix($prefixName->getName());
            $sender->sendMessage(TextFormat::colorize(str_replace(["%prefix-name%", "%player%", "%plugin-prefix%"], [$prefixName->getName(), $player->getName(), Prefixes::getInstance()->getProvider()->getMessages()->get("plugin-prefix")], Prefixes::getInstance()->getProvider()->getMessages()->get("prefix-set-succesfuly"))));
        }
    }
}