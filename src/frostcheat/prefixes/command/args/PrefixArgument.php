<?php

namespace frostcheat\prefixes\command\args;

use CortexPE\Commando\args\StringEnumArgument;

use frostcheat\prefixes\prefix\Prefix;
use frostcheat\prefixes\prefix\PrefixManager;

use pocketmine\command\CommandSender;

final class PrefixArgument extends StringEnumArgument
{
    public function getTypeName() : string {
        return "prefix";
    }

    public function canParse(string $testString, CommandSender $sender) : bool {
        return $this->getValue($testString) instanceof Prefix;
    }

    public function parse(string $argument, CommandSender $sender) : ?Prefix {
        return $this->getValue($argument);
    }

    public function getValue(string $string) : ?Prefix {
        return PrefixManager::getInstance()->getPrefix($string);
    }

    public function getEnumValues() : array {
        return array_keys(PrefixManager::getInstance()->getPrefixes());
    }
}