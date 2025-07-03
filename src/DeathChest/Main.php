<?php

namespace DeathChest;

use pocketmine\Server;
use pocketmine\plugin\PluginBase;
use DeathChest\EventListener;
use pocketmine\utils\Config;

class Main extends PluginBase {

    private static Main $instance;
    
    private Config $pluginConfig;

    public function onLoad(): void {
        self::$instance = $this;
        @mkdir($this->getDataFolder());
        $this->saveResource("config.yml");
        $this->pluginConfig = new Config($this->getDataFolder() . "config.yml", Config::YAML);
    }

    public function onEnable() : void{
        $this->getLogger()->info("§4DeathChest §aON!");
        $this->getServer()->getPluginManager()->registerEvents(new EventListener(), $this);
    }

    public function onDisable() : void {}

    public static function getInstance(): Main {
        return self::$instance;
    }
    
    public function getPluginConfig(): Config {
        return $this->pluginConfig;
    }
}