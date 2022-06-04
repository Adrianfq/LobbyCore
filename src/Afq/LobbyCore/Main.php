<?php

namespace Afq\LobbyCore;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;

use pocketmine\event\Listener;
use pocketmine\event\entity\EntityTeleportEvent;
use pocketmine\event\inventory\InventoryTransactionEvent;
use pocketmine\event\player\PlayerExhaustEvent;
use pocketmine\event\player\PlayerItemUseEvent;
use pocketmine\event\player\PlayerJoinEvent;

use pocketmine\item\enchantment\EnchantmentInstance;
use pocketmine\item\enchantment\StringToEnchantmentParser;

use pocketmine\item\ItemFactory;

use pocketmine\player\Player;
use pocketmine\plugin\PluginBase;

use pocketmine\utils\Config;

use pocketmine\world\Position;

use Afq\LobbyCore\task\DelayTask;

class Main extends PluginBase implements Listener {
	
	public $config;

    public function onEnable() : void {
        $this->getServer()->getPluginManager()->registerEvents($this, $this);
		if(!file_exists($this->getDataFolder() . "Config.yml")) {
            $this->config = (new Config($this->getDataFolder() . "Config.yml", Config::YAML, [
            # BY AFQ ❤️ 
    				"Spawn" => [
					"x" => 0.5,
					"y" => 5,
					"z" => 0.5,
					"world" => "world",
				]
            ]));
        } else {
            $this->config = (new Config($this->getDataFolder() . "Config.yml", Config::YAML, []));
        }
    }

	public function onCommand(CommandSender $player, Command $command, string $label, array $args): bool {
        switch ($command) {
            case "hub":
                if ($player instanceof Player) {
					$config = $this->config->get("Spawn");
					if (!$this->getServer()->getWorldManager()->isWorldLoaded($config["world"])) {
						$this->getServer()->getWorldManager()->loadWorld($config["world"]);
					}
					$player->teleport(new Position($config["x"], $config["y"], $config["z"], $this->getServer()->getWorldManager()->getWorldByName($config["world"])));
                }
                break;
			case "lobby":
				if ($player instanceof Player) {
					$config = $this->config->get("Spawn");
					if (!$this->getServer()->getWorldManager()->isWorldLoaded($config["world"])) {
						$this->getServer()->getWorldManager()->loadWorld($config["world"]);
					}
					$player->teleport(new Position($config["x"], $config["y"], $config["z"], $this->getServer()->getWorldManager()->getWorldByName($config["world"])));
				}
				break;
			case "spawn":
                if ($player instanceof Player) {
					$config = $this->config->get("Spawn");
					if (!$this->getServer()->getWorldManager()->isWorldLoaded($config["world"])) {
						$this->getServer()->getWorldManager()->loadWorld($config["world"]);
					}
					$player->teleport(new Position($config["x"], $config["y"], $config["z"], $this->getServer()->getWorldManager()->getWorldByName($config["world"])));
                }
                break;
        }
        return true;
    }
	
	public function onEntityTeleport(EntityTeleportEvent $event) {
        $player = $event->getEntity();
        if($player instanceof Player){
			$toWorld = $event->getTo()->getWorld()->getFolderName();
			if ($event->getFrom()->getWorld()->getFolderName() != $toWorld) {
				if ($this->getServer()->getWorldManager()->getDefaultWorld()->getFolderName() == $toWorld) {
					$this->getScheduler()->scheduleDelayedTask(new DelayTask($this, $player->getName()), 20);
					return;
				}
				$player->getInventory()->clearAll();
				$player->getArmorInventory()->clearAll();
			}
        }
    }
	
	public function onInventoryTransaction(InventoryTransactionEvent $event) {
        $player = $event->getTransaction()->getSource();
        if($this->getServer()->getWorldManager()->getDefaultWorld()->getFolderName() == $player->getWorld()->getFolderName()) {
            if(!$this->getServer()->isOp($player->getName())){
                $event->cancel();
            }
        }
    }
	
	public function onExhaust(PlayerExhaustEvent $event){
        $player = $event->getPlayer();
        if($this->getServer()->getWorldManager()->getDefaultWorld()->getFolderName() == $player->getWorld()->getFolderName()) {
            $event->cancel();
        }
    }
	
	public function onJoin(PlayerJoinEvent $event) {
		$player = $event->getPlayer();
				$config = $this->config->get("Spawn");
		if (!$this->getServer()->getWorldManager()->isWorldLoaded($config["world"])) {
			$this->getServer()->getWorldManager()->loadWorld($config["world"]);
		}
		$player->teleport(new Position($config["x"], $config["y"], $config["z"], $this->getServer()->getWorldManager()->getWorldByName($config["world"])));
        $player->setSpawn(new Position($config["x"], $config["y"], $config["z"], $this->getServer()->getWorldManager()->getWorldByName($config["world"])));
		$player->getInventory()->setHeldItemIndex(0);
        $player->getInventory()->clearAll();
        $player->getArmorInventory()->clearAll();
        $player->getEffects()->clear();
        $player->setHealth(20);
        $player->getHungerManager()->setFood(20);
	}

	public function Spawn(Player $player) {
		$config = $this->config->get("Spawn");
		if (!$this->getServer()->getWorldManager()->isWorldLoaded($config["world"])) {
			$this->getServer()->getWorldManager()->loadWorld($config["world"]);
		}
		$player->teleport(new Position($config["x"], $config["y"], $config["z"], $this->getServer()->getWorldManager()->getWorldByName($config["world"])));
        $player->setSpawn(new Position($config["x"], $config["y"], $config["z"], $this->getServer()->getWorldManager()->getWorldByName($config["world"])));
		$player->getInventory()->setHeldItemIndex(0);
        $player->getInventory()->clearAll();
        $player->getArmorInventory()->clearAll();
        $player->getEffects()->clear();
        $player->setHealth(20);
        $player->getHungerManager()->setFood(20);
	}

}
