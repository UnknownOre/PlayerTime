<?php
declare(strict_types=1);

namespace UnknownOre\PlayerTime\command;

use UnknownOre\PlayerTime\command\argument\TimeArgument;
use UnknownOre\PlayerTime\libs\CortexPE\Commando\args\IntegerArgument;
use UnknownOre\PlayerTime\libs\CortexPE\Commando\BaseCommand;
use UnknownOre\PlayerTime\libs\CortexPE\Commando\constraint\InGameRequiredConstraint;
use UnknownOre\PlayerTime\Main;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use pocketmine\utils\TextFormat as C;
use pocketmine\world\World;
use function min;

class PlayerTimeCommand extends BaseCommand{

	public function __construct(Main $plugin){
		parent::__construct($plugin, "playertime", "set your virtual time.", ["ptime"]);
		$this->setPermission("playertime.use");
	}

	protected function prepare():void{
		$this->addConstraint(new InGameRequiredConstraint($this));
		$this->registerSubCommand(new Reset($this->plugin));
		$this->registerArgument(0, new IntegerArgument("time"));
		$this->registerArgument(0, new TimeArgument("time"));
	}

	/**
	 * @param Player $sender
	 * @param string $aliasUsed
	 * @param array $args
	 * @return void
	 */
	public function onRun(CommandSender $sender, string $aliasUsed, array $args):void{
		$t = $args["time"];

		$t = min(max($t, 0), World::TIME_FULL);

		/** @var Main $pl */
		$pl = $this->plugin;
		$pl->setTime($sender, $t);

		$sender->sendMessage(C::GREEN . "Your time has been set to " . C::YELLOW . $t . C::GRAY . ".");
	}
}