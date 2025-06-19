<?php

namespace frostcheat\prefixes\session;

use frostcheat\prefixes\Prefixes;

use IvanCraft623\RankSystem\RankSystem;
use IvanCraft623\RankSystem\tag\Tag;
use IvanCraft623\RankSystem\session\Session as RankSession;

use pocketmine\utils\SingletonTrait;
use pocketmine\utils\TextFormat;

class SessionManager
{
    use SingletonTrait;

    /** @var Session[] */
    private array $sessions = [];

    /**
     * SessionManager construct.
     */
    public function load(): void
    {
        $this->sessions = [];
        # Register players
        foreach (Prefixes::getInstance()->getProvider()->getSessions() as $uuid => $data)
            $this->addSession((string) $uuid, $data);
    }
    
    public function checkRankSystem(): void {
        $pluginManager = Prefixes::getInstance()->getServer()->getPluginManager();
    
        if ($pluginManager->getPlugin("RankSystem") !== null && Prefixes::getInstance()->getConfig()->getNested("rank-system-chat", false)) {
            Prefixes::getInstance()->getLogger()->notice("RankSystem chat extension activated");
    
            $placeholder = Prefixes::getInstance()->getConfig()->getNested("rank-system-prefix-placeholder", "prefix");
    
            RankSystem::getInstance()->getTagManager()->registerTag(new Tag($placeholder, function (RankSession $user): string {
                $uuid = $user->getName();
                $session = Prefixes::getInstance()->getSessionManager()->getSession($uuid);
    
                $prefixName = $session?->getPrefix();
                $prefix = $prefixName !== null
                    ? Prefixes::getInstance()->getPrefixManager()->getPrefix($prefixName)?->getFormat()
                    : "";
    
                return TextFormat::colorize($prefix ?? "");
            }));
        }
    }    

    /**
     * @return array
     */
    public function getSessions(): array
    {
        return $this->sessions;
    }

    /**
     * @param string $uuid
     * @return Session|null
     */
    public function getSession(string $uuid): ?Session
    {
        return $this->sessions[$uuid] ?? null;
    }

    /**
     * @param string $uuid
     * @param array $data
     */
    public function addSession(string $uuid, array $data): void
    {
        $this->sessions[$uuid] = new Session($uuid, $data);
    }
}