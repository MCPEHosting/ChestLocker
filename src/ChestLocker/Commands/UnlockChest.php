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

use pocketmine\player\Player;
use pocketmine\plugin\PluginBase;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;

use ChestLocker\Main;

class UnlockChest extends Command{
	public function __construct(Main $plugin){
        parent::__construct("unlockchest");
		$this->plugin = $plugin;
	}

    public function execute(CommandSender $sender, string $commandLabel, array $args)
    {
        if ($sender->hasPermission("cmd.unlockchest")) {
            //Player Sender
            if ($sender instanceof Player) {
                if ($this->plugin->getCommandStatus($sender->getName()) == 0 || $this->plugin->getCommandStatus($sender->getName()) == 1) {
                    $this->plugin->setCommandStatus(2, $sender->getName());
                    $sender->sendMessage($this->plugin->translateColors("&", Main::PREFIX . "&2" . Main::ITEM_NAME . " unlock command enabled. Click the " . Main::ITEM_NAME_2 . " to unlock"));
                } else {
                    $this->plugin->setCommandStatus(0, $sender->getName());
                    $sender->sendMessage($this->plugin->translateColors("&", Main::PREFIX . "&4" . Main::ITEM_NAME . " unlock command disabled."));
                }
            } //Console Sender
            else {
                $sender->sendMessage($this->plugin->translateColors("&", Main::PREFIX . "&cYou can only perform this command as a player"));
                return true;
            }
        } else {
            $sender->sendMessage($this->plugin->translateColors("&", "&cYou don't have permissions to use this command"));
        }
        return true;
    }
}
