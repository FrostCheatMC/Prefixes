<?php

namespace frostcheat\prefixes\session;

use frostcheat\prefixes\Prefixes;

use pocketmine\lang\Translatable;
use pocketmine\player\chat\ChatFormatter;
use pocketmine\utils\TextFormat;

class SessionChatFormatter implements ChatFormatter
{

    public function __construct(
        private Session $session
    ) {}

    /**
     * @inheritDoc
     */
    public function format(string $username, string $message): Translatable|string
    {
        $player = Prefixes::getInstance()->getServer()->getPlayerExact($username);
        if ($this->session->getPrefix() !== null) {
            $prefix = Prefixes::getInstance()->getPrefixManager()->getPrefix($this->session->getPrefix());
            if ($prefix !== null) {
                return TextFormat::colorize(str_replace(["%message%", "%name%", "%prefix%"], [$message, $player->getName(), $prefix->getFormat()], $this->session->getChatFormat()));
            } else {
                return TextFormat::colorize(str_replace(["%message%", "%name%", "%prefix%"], [$message, $player->getName(), ""], $this->session->getChatFormat()));
            }
        }
        return TextFormat::colorize(str_replace(["%message%", "%name%", "%prefix%"], [$message, $player->getName(), ""], $this->session->getChatFormat()));
    }
}