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

use alvin0319\GroupsAPI\GroupsAPI;

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
        //groupsapi support (beta)
        if($this->getConfig()->get("rank-plugin") == "GroupsAPI"){
            if(!class_exists(GroupsAPI::class)){
                $rank = $this->getConfig()->get("default-rank-name");
            } else {
                $member = GroupsAPI::getInstance()->getMemberManager()->getMember($player->getName());
                if($member === null){
                    $rank = $this->getConfig()->get("default-rank-name");
                } else{
	                $group = $member->getHighestGroup();
	                if($group === null){
                        $rank = $this->getConfig()->get("default-rank-name");
		            } else {
                        $rank = $group->getName();
                    }
                }
            }
        } else { //default rank plugin is PurePerms
            if (!class_exists(UserDataManager::class)){
                $rank = $this->getConfig()->get("default-rank-name");
            } else {
                $rank = UserDataManager::getGroup($player)->getName();
            }
        } 
        if($this->getConfig()->get("join-message") !== false){
            $event->setJoinMessage(str_replace(["{name}", "{rank}"], [$name, $rank], $this->getConfig()->get("join-message")));
        }
        if($this->getConfig()->get("guardian-effect") !== false){
            $pk = LevelEventPacket::create(LevelEvent::GUARDIAN_CURSE, 1, $player->getPosition());	
            $player->getNetworkSession()->sendDataPacket($pk);
        } 
    }
	
    public function onLogin(PlayerLoginEvent $event){
        $player = $event->getPlayer();
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
        if($this->getConfig()->get("rank-plugin") == "GroupsAPI"){
            if(!class_exists(GroupsAPI::class)){
                $rank = $this->getConfig()->get("default-rank-name");
            } else {
                $member = GroupsAPI::getInstance()->getMemberManager()->getMember($player->getName());
                if($member === null){
                    $rank = $this->getConfig()->get("default-rank-name");
                } else{
	            $group = $member->getHighestGroup();
	            if($group === null){
                        $rank = $this->getConfig()->get("default-rank-name");
		    } else {
                        $rank = $group->getName();
                    }
                }
            }
        } else { //default rank plugin is PurePerms
            if (!class_exists(UserDataManager::class)){
                $rank = $this->getConfig()->get("default-rank-name");
            } else {
                $rank = UserDataManager::getGroup($player)->getName();
            }
        } 
        if($this->getConfig()->get("left-message") !== false){
            $event->setQuitMessage(str_replace(["{name}", "{rank}"], [$name, $rank], $this->getConfig()->get("left-message")));
        }
    }
}
