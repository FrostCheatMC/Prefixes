<?php

namespace frostcheat\prefixes\session;

use frostcheat\prefixes\Prefixes;

use pocketmine\player\Player;

class Session
{
    private string $uuid;
    private string $name;
    private ?string $prefix;
    private SessionChatFormatter $chatFormatter;
    private Player $player;

    public function __construct(string $name, array $data)
    {
        $this->name = $name;
        $this->prefix = $data["prefix"];

        $this->chatFormatter = new SessionChatFormatter($this);
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getPrefix(): ?string
    {
        return $this->prefix;
    }

    public function setPrefix(?string $prefix): void
    {
        $this->prefix = $prefix;
    }

    public function getChatFormatter(): SessionChatFormatter
    {
        return $this->chatFormatter;
    }

    public function getPlayer(): ?Player
    {
        return Prefixes::getInstance()->getServer()->getPlayerExact($this->getName());
    }

    public function getChatFormat() : string {
        if ($this->getPrefix() === null) {
            return Prefixes::getInstance()->getConfig()->getNested("chat-format", "&7%name%: &f%message%");
        } else {
            return Prefixes::getInstance()->getConfig()->getNested("chat-format-prefix", "%prefix% &7%name%: &f%message%");
        }
    }

    public function getData(): array
    {
        return [
            'prefix' => $this->getPrefix(),
        ];
    }
}