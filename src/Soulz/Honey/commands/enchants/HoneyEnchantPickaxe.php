<?php

namespace Soulz\Honey\commands\enchants;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\command\utils\CommandException;
use pocketmine\item\enchantment\Enchantment;
use pocketmine\item\enchantment\EnchantmentInstance;
use pocketmine\item\Pickaxe;
use pocketmine\network\mcpe\protocol\types\Enchant;
use pocketmine\Player;
use pocketmine\utils\TextFormat;
use pocketmine\utils\TextFormatJsonObject;
use Soulz\Honey\Honey;
use Soulz\Honey\Utils;

class HoneyEnchantPickaxe extends Command{

    public function __construct(){
        parent::__construct("hce");
        $this->setDescription("Add the Honey Enchant");
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args): void{
        if(!$sender->isOp()){
            $sender->sendMessage(TextFormat::RED . "No permission.");
            return;
        }
        if(!$sender instanceof Player){
            $sender->sendMessage(TextFormat::RED . "Please use this command in-game");
            return;
        }
        $item = $sender->getInventory()->getItemInHand();
        if(!$item instanceof Pickaxe) {
            $sender->sendMessage(TextFormat::RED . "You have to be holding a pickaxe.");
            return;
        }
        if(!isset($args[0])){
            $sender->sendMessage("Usage: /$commandLabel {level}");
            return;
        }
        $level = intval($args[0]);
        $item->addEnchantment(new EnchantmentInstance(Honey::getInstance()->getHoneyEnchantPickaxe(), $level ?? 1));
        $lore = $item->getLore();
        foreach($lore as $key => $datum){
            if(strpos($datum, "Honey ") !== false){
                unset($lore[$key]);
                $lore[] = TextFormat::RESET . TextFormat::LIGHT_PURPLE . "Honey " . Utils::lvlToRomanNum($level);
            }else{
                $lore[] = TextFormat::RESET . TextFormat::LIGHT_PURPLE . "Honey " . Utils::lvlToRomanNum($level);
            }
        }
        if(empty($lore)){
            $lore[] = TextFormat::RESET . TextFormat::LIGHT_PURPLE . "Honey " . Utils::lvlToRomanNum($level);
        }
        $item->setLore($lore);
        $sender->getInventory()->setItemInHand($item);
        $sender->sendMessage(TextFormat::GREEN . "Enchanted Pickaxe!");
    }
}
