<?php
declare(strict_types=1);

namespace UnknownOre\PlayerTime\command;

use UnknownOre\PlayerTime\libs\CortexPE\Commando\BaseSubCommand;
use UnknownOre\PlayerTime\libs\CortexPE\Commando\constraint\InGameRequiredConstraint;
use UnknownOre\PlayerTime\Main;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use pocketmine\utils\TextFormat as C;

class Reset extends BaseSubCommand{

	public function __construct(Main $plugin){
		parent::__construct($plugin, "reset", "reset your time.");
	}

	protected function prepare():void{
		$this->addConstraint(new InGameRequiredConstraint($this));
	}

	/**
	 * @param Player $sender
	 * @param string $aliasUsed
	 * @param array $args
	 * @return void
	 */
	public function onRun(CommandSender $sender, string $aliasUsed, array $args):void{
		/** @var Main $pl */
		$pl = $this->plugin;

		$pl->clearTime($sender);
		$sender->sendMessage(C::GREEN . "Your time has been reset" . C::GRAY . ".");
	}
}