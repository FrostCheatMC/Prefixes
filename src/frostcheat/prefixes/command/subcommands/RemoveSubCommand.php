<?php

namespace frostcheat\prefixes\command\subcommands;

use CortexPE\Commando\args\RawStringArgument;
use CortexPE\Commando\BaseSubCommand;

use frostcheat\prefixes\Prefixes;

use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use pocketmine\Server;
use pocketmine\utils\TextFormat;

class RemoveSubCommand extends BaseSubCommand
{
    public function __construct()
    {
        parent::__construct("remove", "Remove prefix to user");
        $this->setPermission("prefixes.command.remove");
    }

    /**
     * @inheritDoc
     */
    protected function prepare(): void
    {
        $this->registerArgument(0, new RawStringArgument("playerName"));
    }

    public function onRun(CommandSender $sender, string $aliasUsed, array $args): void
    {
        $player = Server::getInstance()->getPlayerExact($args["playerName"]);

        if (!($player instanceof Player)) {
            $player = Server::getInstance()->getOfflinePlayer($args["playerName"]);
            if ($player !== null) {
                foreach (Prefixes::getInstance()->getSessionManager()->getSessions() as $uuid => $data) {
                    if (strtolower($player->getName()) === strtolower($data->getName())) {
                        if ($data->getPrefix() === null) {
                            $sender->sendMessage(TextFormat::colorize(str_replace(["%plugin-prefix%", "%player%"], [Prefixes::getInstance()->getProvider()->getMessages()->get("plugin-prefix"), $player->getName()], Prefixes::getInstance()->getProvider()->getMessages()->get("player-remove-player-no-prefix"))));
                            return;
                        }
                        $sender->sendMessage(TextFormat::colorize(str_replace(["%prefix-name%","%plugin-prefix%", "%player%"], [$data->getPrefix(), Prefixes::getInstance()->getProvider()->getMessages()->get("plugin-prefix"), $player->getName()], Prefixes::getInstance()->getProvider()->getMessages()->get("player-remove-succesfuly"))));
                        $data->setPrefix(null);
                        return;
                    }
                }
            }
            $sender->sendMessage(TextFormat::colorize(str_replace("%plugin-prefix%", Prefixes::getInstance()->getProvider()->getMessages()->get("plugin-prefix"), Prefixes::getInstance()->getProvider()->getMessages()->get("prefix-set-no-player"))));
            return;
        }

        $session = Prefixes::getInstance()->getSessionManager()->getSession($player->getName());
        if ($session !== null) {
            if ($session->getPrefix() === null) {
                $sender->sendMessage(TextFormat::colorize(str_replace(["%plugin-prefix%", "%player%"], [Prefixes::getInstance()->getProvider()->getMessages()->get("plugin-prefix"), $player->getName()], Prefixes::getInstance()->getProvider()->getMessages()->get("player-remove-player-no-prefix"))));
                return;
            }
            $prefix = $session->getPrefix();
            $session->setPrefix(null);
            $sender->sendMessage(TextFormat::colorize(str_replace(["%plugin-prefix%", "%prefix-name%", "%player%"], [Prefixes::getInstance()->getProvider()->getMessages()->get("plugin-prefix"), $prefix, $player->getName()], Prefixes::getInstance()->getProvider()->getMessages()->get("player-remove-succesfuly"))));
        }
    }
}