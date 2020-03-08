<?php

declare(strict_types=1);

namespace hcf\utils;

use pocketmine\plugin\PluginException;
use pocketmine\utils\TextFormat;

class Translation {

    /*
    Translation::getMessage("exampleMessage", [
        "player" => "PlayerName"
    ]);
    */
    const MESSAGES = [
        "exampleMessage" => TextFormat::RED . "This is an example {player}."
    ];

    /**
     * @param string $identifier
     * @param array $args
     *
     * @return string
     */
    public static function getMessage(string $identifier, array $args = []): string {
        if(!isset(self::MESSAGES[$identifier])) {
            throw new PluginException("Invalid identifier: $identifier");
        }
        $message = self::MESSAGES[$identifier];
        foreach($args as $arg => $value) {
            $message = str_replace("{" . $arg . "}", $value . TextFormat::RESET . TextFormat::GRAY, $message);
        }
        return (string)$message;
    }
}