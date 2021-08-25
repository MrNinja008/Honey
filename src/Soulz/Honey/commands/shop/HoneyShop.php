<?php

namespace Soulz\Honey\commands\shop;

use muqsit\invmenu\InvMenu;
use muqsit\invmenu\transaction\InvMenuTransaction;
use muqsit\invmenu\transaction\InvMenuTransactionResult;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\item\ItemFactory;
use pocketmine\item\ItemIds;
use pocketmine\nbt\tag\IntTag;
use pocketmine\Player;
use pocketmine\utils\TextFormat;
use Soulz\Honey\Utils;

class HoneyShop extends Command{

    public function __construct(){
        parent::__construct("Honeyshop");
        $this->setDescription("Open Honey Shop");
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args): void{
        if(!$sender instanceof Player){
            $sender->sendMessage(TextFormat::RED . "Please execute this command in-game!");
            return;
        }
        $shopInfo = Utils::getConfig()->get("shop");
        $menu = InvMenu::create(InvMenu::TYPE_DOUBLE_CHEST);
        $inv = $menu->getInventory();
        foreach($shopInfo as $key => $data){
            $item = ItemFactory::get($data["id"], $data["meta"], $data["count"]);
            $lore = [];
            foreach($data["lore"] as $datum){
                $lore[] = TextFormat::colorize($datum);
            }
            $item->setLore($lore);
            $item->setNamedTagEntry(new IntTag("honeyprice", $data["price"]));
            $inv->setItem($key, $item);
        }
        $menu->setListener(function(InvMenuTransaction $transaction)use($sender): InvMenuTransactionResult{
            $itemClicked = $transaction->getItemClicked();
            $nbt = $itemClicked->getNamedTag();
            $honey = Utils::getHoney($sender->getName());
            $price = $nbt->getTag("honeyprice")->getValue();
            if($honey <= $price){
                $sender->sendMessage(TextFormat::GREEN . "You do not have enough Honey.");
                $transaction->getPlayer()->removeWindow($transaction->getAction()->getInventory());
                return $transaction->discard();
            }
            if(!$sender->getInventory()->canAddItem($itemClicked)){
                $sender->sendMessage(TextFormat::RED . "Can't add this item");
                return $transaction->discard();
            }
            $transaction->getPlayer()->removeWindow($transaction->getAction()->getInventory());
            Utils::reduceHoney($sender->getName(), $price);
            $sender->getInventory()->addItem($itemClicked);
            return $transaction->discard();
        });
        $menu->setName(TextFormat::GREEN . "Your Honey: " . Utils::getHoney($sender->getName()));
        $menu->send($sender);
    }
}
