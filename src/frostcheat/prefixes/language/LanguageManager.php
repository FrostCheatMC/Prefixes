<?php

namespace frostcheat\prefixes\language;

use frostcheat\prefixes\Prefixes;
use frostcheat\prefixes\provider\Provider;

use pocketmine\utils\SingletonTrait;

class LanguageManager
{
    use SingletonTrait;

    /**
     * @var array<string, Language>
     */
    private array $languages = [];
    private string $default_language;

    public function load(): void
    {
        $this->languages = [];
        foreach (Provider::getInstance()->getLanguages() as $name => $data) {
            $this->addLanguage($name, $data);
        }
        $this->default_language = Prefixes::getInstance()->getConfig()->get("default-language", "en_us");
    }

    /**
     * @return array
     */
    public function getLanguages(): array
    {
        return $this->languages;
    }

    /**
     * @param string $uuid
     * @return Language|null
     */
    public function getLanguage(string $uuid): ?Language
    {
        return $this->languages[$uuid] ?? null;
    }

    /**
     * @param string $name
     * @param array $data
     */
    public function addLanguage(string $name, array $data): void
    {
        $this->languages[strtolower($name)] = new Language($name);
    }

    public function getDefaultLanguage(): string
    {
        return $this->default_language;
    }

    public function setDefaultLanguage(string $name): void
    {
        $this->default_language = $name;
    }
}