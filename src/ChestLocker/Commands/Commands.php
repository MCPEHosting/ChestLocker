<?php

/*
 * ChestLocker (v1.2) by EvolSoft
 * Developer: EvolSoft (Flavius12)
 * Website: http://www.evolsoft.tk
 * Date: 27/12/2014 03:32 PM (UTC)
 * Copyright & License: (C) 2014-2017 EvolSoft
 * Licensed under MIT (https://github.com/EvolSoft/ChestLocker/blob/master/LICENSE)
 */

namespace ChestLocker\Commands;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;

use ChestLocker\Main;

class Commands extends Command{

	public function __construct(protected Main $plugin){
        parent::__construct("chestlocker");
		$this->plugin = $plugin;
        $this->setPermission("cmd.chestlocker");
	}

    public function execute(CommandSender $sender, string $commandLabel, array $args)
    {
        if (isset($args[0])) {
            $args[0] = strtolower($args[0]);
            if ($args[0] == "help") {
                if ($sender->hasPermission("cmd.chestlocker.help")) {
                    $sender->sendMessage($this->plugin->translateColors("&", "&c|| &8Available Commands &c||"));
                    $sender->sendMessage($this->plugin->translateColors("&", "&c/chlock info &8> Show info about this plugin"));
                    $sender->sendMessage($this->plugin->translateColors("&", "&c/chlock reload &8> Reload the config"));
                    $sender->sendMessage($this->plugin->translateColors("&", "&c/lockchest &8> Lock a " . Main::ITEM_NAME_2));
                    $sender->sendMessage($this->plugin->translateColors("&", "&c/unlockchest &8> Unlock a " . Main::ITEM_NAME_2));
                } else {
                    $sender->sendMessage($this->plugin->translateColors("&", "&cYou don't have permissions to use this command"));
                }
            } elseif ($args[0] == "reload") {
                if ($sender->hasPermission("cmd.chestlocker.reload")) {
                    $this->plugin->reloadConfig();
                    $sender->sendMessage($this->plugin->translateColors("&", Main::PREFIX . "&aConfiguration Reloaded."));
                } else {
                    $sender->sendMessage($this->plugin->translateColors("&", "&cYou don't have permissions to use this command"));
                }
            } elseif ($args[0] == "info") {
                if ($sender->hasPermission("cmd.chestlocker.info")) {
                    $sender->sendMessage($this->plugin->translateColors("&", Main::PREFIX . "&8ChestLocker &cv" . Main::VERSION . " &8developed by&c " . Main::PRODUCER));
                    $sender->sendMessage($this->plugin->translateColors("&", Main::PREFIX . "&8Website &c" . Main::MAIN_WEBSITE));
                } else {
                    $sender->sendMessage($this->plugin->translateColors("&", "&cYou don't have permissions to use this command"));
                }
            }
        } else {
            if ($sender->hasPermission("cmd.chestlocker.help")) {
                $sender->sendMessage($this->plugin->translateColors("&", "&c|| &8Available Commands &c||"));
                $sender->sendMessage($this->plugin->translateColors("&", "&c/chlock info &8> Show info about this plugin"));
                $sender->sendMessage($this->plugin->translateColors("&", "&c/chlock reload &8> Reload the config"));
                $sender->sendMessage($this->plugin->translateColors("&", "&c/lockchest &8> Lock a " . Main::ITEM_NAME_2));
                $sender->sendMessage($this->plugin->translateColors("&", "&c/unlockchest &8> Unlock a " . Main::ITEM_NAME_2));
            } else {
                $sender->sendMessage($this->plugin->translateColors("&", "&cYou don't have permissions to use this command"));
            }
        }
    }
}
