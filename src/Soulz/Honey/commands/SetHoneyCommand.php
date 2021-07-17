<?php

namespace Soulz\Honey\commands;

use pocketmine\command\Command; 
use pocketmine\command\CommandSender; 
use pocketmine\command\utils\CommandException;
use pocketmine\utils\Config;
use pocketmine\command\TextFormat as TF;

class SetHoneyCommand extends Command
{
    /** @var Honey */
    private $plugin;

    public function __construct(){
        __construct::("seehoney");
        $this->setDescription("myhoney") # Command Desc shown while being typed
    }
    
    public function execute(CommandSender $sender, Command $cmd, string $commandLabel, array $args): void{
        if(!$sender->isOp){
            $sender->sendMessage(TF::RED.TF::BOLD."Error: ".TF::RESET.TF::GREY."You don't have permission to set honey for ".TF::CYAN."$player")
        }
    }
}
