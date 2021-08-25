<?php

namespace Soulz\Honey;

use pocketmine\utils\Config;
use pocketmine\utils\TextFormat;

class Utils{

    public static function getData(): Config{
        return Honey::getInstance()->getData();
    }

    public static function getConfig(): Config{
        return Honey::getInstance()->getConfig();
    }

    public static function getHoney(string $player): int{
        return self::getData()->get($player, 0);
    }

    public static function setHoney(string $player, int $count): void{
        self::getData()->set($player, $count);
        self::getData()->save();
    }

    public static function addHoney(string $player, int $count): void{
        self::getData()->set($player, $count + self::getHoney($player));
        self::getData()->save();
    }

    public static function reduceHoney(string $player, int $count): void{
        self::getData()->set($player, self::getHoney($player) - $count);
        self::getData()->save();
    }

    public static function getMessage(string $string, array $search = [], array $replace = []): string{
        return TextFormat::colorize(str_replace($search, $replace,self::getConfig()->get("messages")[$string]));
    }

    public static function lvlToRomanNum(int $level) : string{
        $romanNumeralConversionTable = [
            'X'  => 10,
            'IX' => 9,
            'V'  => 5,
            'IV' => 4,
            'I'  => 1
        ];
        $romanString = "";
        while($level > 0){
            foreach($romanNumeralConversionTable as $rom => $arb){
                if($level >= $arb){
                    $level -= $arb;
                    $romanString .= $rom;
                    break;
                }
            }
        }
        return $romanString;
    }
}
