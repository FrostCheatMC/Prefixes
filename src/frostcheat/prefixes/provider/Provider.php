<?php

namespace frostcheat\prefixes\provider;

use frostcheat\prefixes\language\LanguageManager;
use frostcheat\prefixes\Prefixes;
use pocketmine\utils\Config;
use pocketmine\utils\SingletonTrait;
use pocketmine\scheduler\AsyncTask;
use pocketmine\Server;

class Provider {
    use SingletonTrait;

    private Config $messages;

    public function load(): void {
        $dataFolder = Prefixes::getInstance()->getDataFolder();

        @mkdir($dataFolder . 'prefixes', 0777, true);
        @mkdir($dataFolder . 'sessions', 0777, true);

        $this->saveResources();
    }

    public function save(): void {
        $this->saveSessionsAsync();
        $this->savePrefixesAsync();
    }

    public function reload(): void {
        Prefixes::getInstance()->reloadConfig();
        Prefixes::getInstance()->getPrefixManager()->load();
        Prefixes::getInstance()->getLanguageManager()->load();
        $this->getMessages()->reload();
    }

    public function saveResources(): void {
        $plugin = Prefixes::getInstance();
        $plugin->saveDefaultConfig();
        $plugin->saveResource("gui.yml");

        foreach (["es_es", "en_us", "fr_fr", "gr_gr", "pr_br", "rs_rs"] as $lang) {
            $plugin->saveResource("languages/{$lang}.yml");
        }
    }

    public function getMessages(): Config {
        $lang = LanguageManager::getInstance()->getDefaultLanguage();
    
        if (!in_array($lang, ["es_es", "en_us", "fr_fr", "gr_gr", "pr_br", "rs_rs"])) {
            Prefixes::getInstance()->getLogger()->warning("Invalid language format in LanguageManager. Defaulting to en_us.");
            $lang = "en_us";
        }
    
        $path = Prefixes::getInstance()->getDataFolder() . "languages/" . $lang . ".yml";
        return new Config($path, Config::YAML);
    }
    

    public function getSessions(): array {
        return $this->loadConfigsFromDirectory("sessions");
    }

    public function getPrefixes(): array {
        return $this->loadConfigsFromDirectory("prefixes");
    }

    public function getLanguages(): array {
        return $this->loadConfigsFromDirectory("languages");
    }

    private function loadConfigsFromDirectory(string $directory): array {
        $folder = Prefixes::getInstance()->getDataFolder() . $directory . DIRECTORY_SEPARATOR;
        $data = [];

        foreach (glob($folder . '*.yml') as $file) {
            $data[basename($file, '.yml')] = (new Config($file, Config::YAML))->getAll();
        }

        return $data;
    }

    public function savePrefixesAsync(): void {
        $prefixes = [];
        foreach (Prefixes::getInstance()->getPrefixManager()->getPrefixes() as $name => $prefix) {
            $prefixes[$name] = $prefix->getData();
        }
    
        Server::getInstance()->getAsyncPool()->submitTask(new class(serialize($prefixes), Prefixes::getInstance()->getDataFolder()) extends AsyncTask {
            public function __construct(
                private string $serializedPrefixes,
                private string $dataFolder
            ) {}
    
            public function onRun(): void {
                $prefixes = unserialize($this->serializedPrefixes);
                foreach ($prefixes as $name => $data) {
                    yaml_emit_file($this->dataFolder . 'prefixes/' . $name . '.yml', $data);
                }
            }
        });
    }    

    public function saveSessionsAsync(): void {
        $sessions = [];
        foreach (Prefixes::getInstance()->getSessionManager()->getSessions() as $name => $session) {
            $sessions[$name] = $session->getData();
        }
    
        Server::getInstance()->getAsyncPool()->submitTask(new class(serialize($sessions), Prefixes::getInstance()->getDataFolder()) extends AsyncTask {
            public function __construct(
                private string $serializedSessions,
                private string $dataFolder
            ) {}
    
            public function onRun(): void {
                $sessions = unserialize($this->serializedSessions);
                foreach ($sessions as $name => $data) {
                    yaml_emit_file($this->dataFolder . 'sessions/' . $name . '.yml', $data);
                }
            }
        });
    }    
}