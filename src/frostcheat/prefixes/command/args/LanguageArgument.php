<?php

namespace frostcheat\prefixes\command\args;

use frostcheat\prefixes\language\Language;
use frostcheat\prefixes\language\LanguageManager;

use CortexPE\Commando\args\StringEnumArgument;

use pocketmine\command\CommandSender;

class LanguageArgument extends StringEnumArgument
{

    public function getTypeName() : string {
        return "prefix";
    }

    public function canParse(string $testString, CommandSender $sender) : bool {
        return $this->getValue($testString) instanceof Language;
    }

    public function parse(string $argument, CommandSender $sender) : ?Language {
        return $this->getValue($argument);
    }

    public function getValue(string $string) : ?Language {
        return LanguageManager::getInstance()->getLanguage($string);
    }

    public function getEnumValues() : array {
        return array_keys(LanguageManager::getInstance()->getLanguages());
    }
}