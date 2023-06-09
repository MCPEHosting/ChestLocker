<?php

/*
 * ChestLocker (v1.2) by EvolSoft
 * Developer: EvolSoft (Flavius12)
 * Website: http://www.evolsoft.tk
 * Date: 27/12/2014 03:32 PM (UTC)
 * Copyright & License: (C) 2014-2017 EvolSoft
 * Licensed under MIT (https://github.com/EvolSoft/ChestLocker/blob/master/LICENSE)
 */

namespace ChestLocker;

use pocketmine\block\tile\Chest;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\player\PlayerQuitEvent;
use pocketmine\event\block\BlockBreakEvent;

class EventListener implements Listener
{
	
	public function __construct(protected Main $plugin)
	{
		$this->plugin = $plugin;
	}
	
	public function onPlayerJoin(PlayerJoinEvent $event)
	{
		$this->plugin->setCommandStatus(0, $event->getPlayer()->getName());
	}
	
	public function onPlayerQuit(PlayerQuitEvent $event)
	{
		$this->plugin->endCommandSession($event->getPlayer()->getName());
	}
	
	public function onChestOpen(PlayerInteractEvent $event)
	{
		if ($event->getBlock()->getTypeId() == Main::$ITEM_ID) {
			$chest = $event->getPlayer()->getWorld()->getTile($event->getBlock()->getPosition()->asVector3());
			if ($chest instanceof Chest) {
				//Check Command status
				//0
				if ($this->plugin->getCommandStatus($event->getPlayer()->getName()) == 0) {
					//Check bypass permission
					if (!$event->getPlayer()->hasPermission("chestlocker.bypass")) {
						//Check if Chest is registered
						$paired = $chest->getPair();
						if ($this->plugin->isChestRegistered($chest->getPosition()->getWorld()->getFolderName(), $chest->getPosition()->getX(), $chest->getPosition()->getY(), $chest->getPosition()->getZ()) && $this->plugin->getChestOwner($chest->getPosition()->getWorld()->getFolderName(), $chest->getPosition()->getX(), $chest->getPosition()->getY(), $chest->getPosition()->getZ()) != strtolower($event->getPlayer()->getName()) || $paired != null && $this->plugin->isChestRegistered($paired->getPosition()->getWorld()->getFolderName(), $paired->getPosition()->getX(), $paired->getPosition()->getY(), $paired->getPosition()->getZ()) && $this->plugin->getChestOwner($paired->getPosition()->getWorld()->getFolderName(), $paired->getPosition()->getX(), $paired->getPosition()->getY(), $paired->getPosition()->getZ()) != strtolower($event->getPlayer()->getName())) {
							
							$event->cancel();
							$event->getPlayer()->sendMessage($this->plugin->translateColors("&", Main::PREFIX . "&4You aren't the owner of this " . Main::ITEM_NAME_2 . "."));
						}
					}
				}
				
				//1
				if ($this->plugin->getCommandStatus($event->getPlayer()->getName()) == 1) {
					//Check if Chest is registered
					$paired = $chest->getPair();
					if ($this->plugin->isChestRegistered($chest->getPosition()->getWorld()->getFolderName(), $chest->getPosition()->getX(), $chest->getPosition()->getY(), $chest->getPosition()->getZ())) {
						if ($this->plugin->getChestOwner($chest->getPosition()->getWorld()->getFolderName(), $chest->getPosition()->getX(), $chest->getPosition()->getY(), $chest->getPosition()->getZ()) != strtolower($event->getPlayer()->getName()) || $paired != null && $this->plugin->isChestRegistered($paired->getPosition()->getWorld()->getFolderName(), $paired->getPosition()->getX(), $paired->getPosition()->getY(), $paired->getPosition()->getZ()) && $this->plugin->getChestOwner($paired->getPosition()->getWorld()->getFolderName(), $paired->getPosition()->getX(), $paired->getPosition()->getY(), $paired->getPosition()->getZ()) != strtolower($event->getPlayer()->getName())) {
							$event->getPlayer()->sendMessage($this->plugin->translateColors("&", Main::PREFIX . "&4You aren't the owner of this " . Main::ITEM_NAME_2 . "."));
						} else {
							$event->getPlayer()->sendMessage($this->plugin->translateColors("&", Main::PREFIX . "&2" . Main::ITEM_NAME . " already locked"));
						}
					} else {
						$event->getPlayer()->sendMessage($this->plugin->translateColors("&", Main::PREFIX . "&2" . Main::ITEM_NAME . " locked"));
						$this->plugin->lockChest($chest->getPosition()->getWorld()->getFolderName(), $chest->getPosition()->getX(), $chest->getPosition()->getY(), $chest->getPosition()->getZ(), $event->getPlayer()->getName());
						if ($paired != null && !($this->plugin->isChestRegistered($paired->getPosition()->getWorld()->getFolderName(), $paired->getPosition()->getX(), $paired->getPosition()->getY(), $paired->getPosition()->getZ()))) {
							$this->plugin->lockChest($paired->getPosition()->getWorld()->getFolderName(), $paired->getPosition()->getX(), $paired->getPosition()->getY(), $paired->getPosition()->getZ(), $event->getPlayer()->getName());
						}
					}
					$event->cancel();
					$this->plugin->setCommandStatus(0, $event->getPlayer()->getName());
				}
				//2
				if ($this->plugin->getCommandStatus($event->getPlayer()->getName()) == 2) {
					//Check if Chest is registered
					if ($this->plugin->isChestRegistered($chest->getPosition()->getWorld()->getFolderName(), $chest->getPosition()->getX(), $chest->getPosition()->getY(), $chest->getPosition()->getZ())) {
						if ($event->getPlayer()->hasPermission("chestlocker.bypass") == false && $this->plugin->getChestOwner($chest->getPosition()->getWorld()->getFolderName(), $chest->getPosition()->getX(), $chest->getPosition()->getY(), $chest->getPosition()->getZ()) != strtolower($event->getPlayer()->getName())) {
							$event->getPlayer()->sendMessage($this->plugin->translateColors("&", Main::PREFIX . "&4You aren't the owner of this " . Main::ITEM_NAME_2 . "."));
						} else {
							$event->getPlayer()->sendMessage($this->plugin->translateColors("&", Main::PREFIX . "&2" . Main::ITEM_NAME . " unlocked"));
							$this->plugin->unlockChest($chest->getPosition()->getWorld()->getFolderName(), $chest->getPosition()->getX(), $chest->getPosition()->getY(), $chest->getPosition()->getZ(), $this->plugin->getChestOwner($chest->getPosition()->getWorld()->getFolderName(), $chest->getPosition()->getX(), $chest->getPosition()->getY(), $chest->getPosition()->getZ()));
							$paired = $chest->getPair();
							if ($paired != null && $this->plugin->isChestRegistered($paired->getPosition()->getWorld()->getFolderName(), $paired->getPosition()->getX(), $paired->getPosition()->getY(), $paired->getPosition()->getZ())) {
								$this->plugin->unlockChest($paired->getPosition()->getWorld()->getFolderName(), $paired->getPosition()->getX(), $paired->getPosition()->getY(), $paired->getPosition()->getZ(), $this->plugin->getChestOwner($paired->getPosition()->getWorld()->getFolderName(), $paired->getPosition()->getX(), $paired->getPosition()->getY(), $paired->getPosition()->getZ()));
							}
						}
					} else {
						$event->getPlayer()->sendMessage($this->plugin->translateColors("&", Main::PREFIX . "&2" . Main::ITEM_NAME . " not registered"));
					}
					$event->cancel();
					$this->plugin->setCommandStatus(0, $event->getPlayer()->getName());
				}
			}
		}
	}
	
	public function onBlockDestroy(BlockBreakEvent $event)
	{
		$this->cfg = $this->plugin->getConfig()->getAll();
		$player    = $event->getPlayer();
		if ($event->getBlock()->getTypeId() == Main::$ITEM_ID) {
			$chest = $event->getPlayer()->getWorld()->getTile($event->getBlock()->getPosition()->asVector3());
			if ($chest instanceof Chest) {
				$level  = $chest->getPosition()->getWorld()->getFolderName();
				$x      = $chest->getPosition()->getX();
				$y      = $chest->getPosition()->getY();
				$z      = $chest->getPosition()->getZ();
				$paired = $chest->getPair();
				//Check if chest is registered
				if ($this->plugin->isChestRegistered($level, $x, $y, $z)) {
					//Check bypass permission
					if ($event->getPlayer()->hasPermission("chestlocker.bypass") == false && ($this->plugin->isChestRegistered($chest->getPosition()->getWorld()->getFolderName(), $chest->getPosition()->getX(), $chest->getPosition()->getY(), $chest->getPosition()->getZ()) && $this->plugin->getChestOwner($level, $x, $y, $z) != strtolower($event->getPlayer()->getName()) || $paired != null && $this->plugin->isChestRegistered($paired->getPosition()->getWorld()->getFolderName(), $paired->getPosition()->getX(), $paired->getPosition()->getY(), $paired->getPosition()->getZ()) && $this->plugin->getChestOwner($paired->getPosition()->getWorld()->getFolderName(), $paired->getPosition()->getX(), $paired->getPosition()->getY(), $paired->getPosition()->getZ()) != strtolower($event->getPlayer()->getName()))) {
						$player->sendMessage($this->plugin->translateColors("&", Main::PREFIX . "&4You aren't the owner of this " . Main::ITEM_NAME_2 . "."));
						$event->cancel();
					} else {
						$this->plugin->unlockChest($level, $x, $y, $z, $this->plugin->getChestOwner($level, $x, $y, $z));
						if ($paired != null && $this->plugin->isChestRegistered($paired->getPosition()->getWorld()->getFolderName(), $paired->getPosition()->getX(), $paired->getPosition()->getY(), $paired->getPosition()->getZ()))
							$this->plugin->unlockChest($level, $paired->getPosition()->getX(), $y, $paired->getPosition()->getZ(), $this->plugin->getChestOwner($level, $paired->getPosition()->getX(), $y, $paired->getPosition()->getZ()));
					}
				}
			}
		}
	}
}
