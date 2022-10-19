<?php
declare(strict_types=1);

namespace UnknownOre\PlayerTime\command\argument;

use UnknownOre\PlayerTime\libs\CortexPE\Commando\args\StringEnumArgument;
use pocketmine\command\CommandSender;
use pocketmine\world\World;
use function array_keys;

class TimeArgument extends StringEnumArgument{

	private array $values = [
		"day" => World::TIME_DAY,
		"noon" => World::TIME_NOON,
		"sunset" => World::TIME_SUNSET,
		"night" => World::TIME_NIGHT,
		"midnight" => World::TIME_MIDNIGHT,
		"sunrise" => World::TIME_SUNRISE];

	public function parse(string $argument, CommandSender $sender){
		return $this->values[$argument];
	}

	public function getTypeName():string{
		return "time";
	}

	public function getEnumName():string{
		return "time";
	}

	public function getEnumValues():array{
		return array_keys($this->values);
	}
}