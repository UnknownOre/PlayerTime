<?php
declare(strict_types=1);

namespace UnknownOre\PlayerTime;

use UnknownOre\PlayerTime\command\PlayerTimeCommand;
use UnknownOre\PlayerTime\libs\CortexPE\Commando\libs\muqsit\simplepackethandler\SimplePacketHandler;
use UnknownOre\PlayerTime\libs\CortexPE\Commando\PacketHooker;
use pocketmine\event\EventPriority;
use pocketmine\event\player\PlayerQuitEvent;
use pocketmine\network\mcpe\NetworkSession;
use pocketmine\network\mcpe\protocol\SetTimePacket;
use pocketmine\player\Player;
use pocketmine\plugin\PluginBase;

class Main extends PluginBase{

	private array $players = [];

	public function onEnable():void{
		$this->getServer()->getCommandMap()->register("PlayerTime",new PlayerTimeCommand($this));
		$packetMonitor = SimplePacketHandler::createInterceptor($this);
		$packetMonitor->interceptOutgoing(function(SetTimePacket $packet, NetworkSession $session): bool{
			$player = $session->getPlayer();
			if(isset($this->players[$player->getId()]) && $packet->time !== $this->players[$player->getId()]){
				return false;
			}

			return true;
		});

		$this->getServer()->getPluginManager()->registerEvent(PlayerQuitEvent::class,function(PlayerQuitEvent $event): void{
			unset($this->players[$event->getPlayer()->getId()]);
		},EventPriority::NORMAL, $this);

		PacketHooker::register($this);
	}

	public function setTime(Player $player, int $time): void{
		$this->players[$player->getId()] = $time;
		$player->getNetworkSession()->sendDataPacket(SetTimePacket::create($time));
	}

	public function clearTime(Player $player): void{
		unset($this->players[$player->getId()]);
		$player->getNetworkSession()->sendDataPacket(SetTimePacket::create($player->getWorld()->getTime()));
	}


}