<?php

namespace Soulz\Honey;

use pocketmine\event\block\BlockBreakEvent;
use pocketmine\event\Listener;
use pocketmine\item\enchantment\Enchantment;
use pocketmine\item\Pickaxe;
use pocketmine\network\mcpe\protocol\types\Enchant;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;
use pocketmine\utils\TextFormat;
use Soulz\Honey\commands\MyHoneyCommand;
use Soulz\Honey\commands\shop\HoneyShop;
use Soulz\Honey\commands\enchants\HoneyEnchantPickaxe;
use muqsit\invmenu\InvMenuHandler;

class Honey extends PluginBase implements Listener{

    /** @var self */
    private static $instance;

    private $data;

    /**
     * @var Enchantment
     */
    private $honeyEnchant;

    public function onEnable(): void{
        if(!InvMenuHandler::isRegistered()) InvMenuHandler::register($this);
        $this->saveDefaultConfig();
        $this->getServer()->getPluginManager()->registerEvents($this,$this);
        $cmds = [
            new MyHoneyCommand(),
            new HoneyShop(),
            new HoneyEnchantPickaxe()
        ];
        $this->getServer()->getCommandMap()->registerAll("HoneyCommands", $cmds);
        $this->data = new Config($this->getDataFolder() . "honeydata.yml", Config::YAML);
        $id = $this->getConfig()->get("ce-id", 200);
        $name = TextFormat::colorize($this->getConfig()->get("ce-name", "Honey++"));
        $maxLevel = $this->getConfig()->get("max-level", 10);
        Enchantment::registerEnchantment($this->honeyEnchant = new Enchantment($id, $name, Enchantment::RARITY_COMMON, Enchantment::SLOT_PICKAXE, Enchantment::SLOT_NONE, $maxLevel));
    }

    public function onLoad(): void{
        self::$instance = $this;
    }

    /**
     * @return Enchantment
     */
    public function getHoneyEnchantPickaxe(): Enchantment{
        return $this->honeyEnchant;
    }

    /** 
    * @var Honey
    */
    public static function getInstance(): self{
        return self::$instance;
    }

    public function getData(): Config{
        return $this->data;
    }

    public function onBreak(BlockBreakEvent $event): void{
        $item = $event->getItem();
        $player = $event->getPlayer();
        $randomizer = mt_rand(0, 100);
        $chance = $this->getConfig()->get("chance", 2);
        if($randomizer <= $chance){
            $amount = mt_rand(1, 3);
            $id = $this->getConfig()->get("ce-id", 200);
            if($item instanceof Pickaxe && $item->hasEnchantment($id)){
                $level = $item->getEnchantment($id)->getLevel();
                $amount = mt_rand(1, 3) * ($level + 1);
            }
            $player->sendMessage(TextFormat::RED . "[+] " . TextFormat::AQUA . $amount . TextFormat::RED . " Honey");
            Utils::addHoney($player->getName(), $amount);
        }
    }

}
