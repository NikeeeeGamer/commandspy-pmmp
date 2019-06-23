<?php

declare(strict_types = 1);

namespace commandspy;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\event\Listener;
use pocketmine\event\server\ServerCommandEvent;
use pocketmine\event\player\PlayerCommandPreprocessEvent;
use pocketmine\Player;
use pocketmine\plugin\PluginBase;

class Main extends PluginBase implements Listener {
	public $commandspylist = [];

	public function onEnable() {
		$this->getServer()->getPluginManager()->registerEvents($this, $this);
	}

	 public function onCommand(CommandSender $sender, Command $cmd, string $label, array $args): bool {
		if($sender instanceof Player) {
			if(isset($args[0])){
                		switch($args[0]){
                    			case 'on':
						$sender->sendMessage("§2Successfully enabled CommandSpy");
						$this->commandspylist[$sender->getName()] = $sender;
						break;
					case 'off':
						$sender->sendMessage("§2Successfully disabled CommandSpy");
						unset($this->commandspylist[$sender->getName()]);
						break;
				}
			} elseif(!isset($this->commandspylist[$sender->getName()])) {
				$sender->sendMessage("§2Successfully enabled CommandSpy");
				$this->commandspylist[$sender->getName()] = $sender;
			} else {
				$sender->sendMessage("§2Successfully disabled CommandSpy");
				unset($this->commandspylist[$sender->getName()]);
			}
			return true;
		}
		return true;
	}

	public function onConsoleCmd(ServerCommandEvent $event) {
		foreach($this->commandspylist as $spy) {
			$spy->sendMessage("§5Console: " . $event->getCommand());
		}
	}

	public function onPlayerCmd(PlayerCommandPreprocessEvent $event) {
		$sender = $event->getPlayer()->getName();
		$command = $event->getMessage();

		foreach($this->commandspylist as $spy) {
			if($command[0] == "/") {
				if(isset($this->commandspylist[$sender])) {
					$spy->sendMessage("§e" . $sender . ": " . $command);
				} else {
					$spy->sendMessage("§b" . $sender . ": " . $command);
				}
			}
		}
	}
 }
