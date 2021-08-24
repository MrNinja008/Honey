<?php

namespace Soulz\Honey\commands;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\Player;
use pocketmine\utils\TextFormat;
use Soulz\Honey\Utils;

class MyHoneyCommand extends Command {

    public function __construct(){
        parent::__construct("honey");
        $this->setDescription("View your honey");
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args): void{
        if(!$sender instanceof Player){
            $sender->sendMessage(TextFormat::RED . "Please execute this command in-game!");
            return;
        }
        $sender->sendMessage(Utils::getMessage("honey-count", ["{honey}"], [Utils::getHoney($sender->getName())]));
    }
}
