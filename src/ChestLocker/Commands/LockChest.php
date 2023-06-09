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
use pocketmine\player\Player;

use ChestLocker\Main;

class LockChest extends Command{
	public function __construct(protected Main $plugin){
        parent::__construct("lockchest");
		$this->plugin = $plugin;
        $this->setPermission("cmd.lockchest");
	}

    public function execute(CommandSender $sender, string $commandLabel, array $args)
    {
        if ($sender->hasPermission("cmd.lockchest")) {
            //Player Sender
            if ($sender instanceof Player) {
                if ($this->plugin->getCommandStatus($sender->getName()) == 0 || $this->plugin->getCommandStatus($sender->getName()) == 2) {
                    $this->plugin->setCommandStatus(1, $sender->getName());
                    $sender->sendMessage($this->plugin->translateColors("&", Main::PREFIX . "&2" . Main::ITEM_NAME . " lock command enabled. Click the " . Main::ITEM_NAME_2 . " to lock"));
                } else {
                    $this->plugin->setCommandStatus(0, $sender->getName());
                    $sender->sendMessage($this->plugin->translateColors("&", Main::PREFIX . "&4" . Main::ITEM_NAME . " lock command disabled."));
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
