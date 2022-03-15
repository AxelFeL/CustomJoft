<?php

namespace AxelFeL\CustomJoft;

use pocketmine\Server;
use pocketmine\player\Player;

use pocketmine\plugin\Plugin;
use pocketmine\plugin\PluginBase;

use pocketmine\utils\Config;

use pocketmine\network\mcpe\protocol\LevelEventPacket;
use pocketmine\network\mcpe\protocol\types\LevelEvent;

use _64FF00\PurePerms\PurePerms;
use _64FF00\PurePerms\DataManager\UserDataManager;

use pocketmine\event\Listener;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\player\PlayerQuitEvent;

class Main extends PluginBase implements Listener {
    
    public function onEnable(): void{
        $this->getServer()->getPluginManager()->registerEvents($this, $this);
        $this->saveResource("config.yml");
    }
    
    public function onJoin(PlayerJoinEvent $event){
        $player = $event->getPlayer();
        $name = $player->getName();
        if (!class_exists(UserDataManger::class)){
            $rank = "";
        } else {
            $rank = UserDataManager::getGroup($player)->getName();
        }
        if($this->getConfig()->get("join-message") !== false){
            $event->setJoinMessage(str_replace(["{name}", "{rank}"], [$name, $rank], $this->getConfig()->get("join-message")));
        }
        if($this->getConfig()->get("guardian-effect") !== false){
            $pk = LevelEventPacket::create(LevelEvent::GUARDIAN_CURSE, 1, $player->getPosition());	
            $player->getNetworkSession()->sendDataPacket($pk);
        }
        if($this->getConfig()->get("join-title") !== false){
            $player->sendTitle(str_replace("{name}", $name, $this->getConfig()->get("join-title")));
        }
        if($this->getConfig()->get("join-subtitle") !== false){
            $player->sendSubTitle(str_replace("{name}", $name, $this->getConfig()->get("join-subtitle")));
        }
    }
    
    public function onQuit(PlayerQuitEvent $event){
        $player = $event->getPlayer();
        $name = $player->getName();
        if (!class_exists(UserDataManager::class)){
            $rank = "";
        } else {
            $rank = UserDataManager::getGroup($player)->getName();
        }
        if($this->getConfig()->get("left-message") !== false){
            $event->setQuitMessage(str_replace(["{name}", "{rank}"], [$name, $rank], $this->getConfig()->get("left-message")));
        }
    }
}
