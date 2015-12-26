<?php

namespace pocketmine\entity;

use pocketmine\network\protocol\AddEntityPacket;
use pocketmine\Player;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\network\protocol\EntityEventPacket;
use pocketmine\item\Item;
use pocketmine\event\entity\EntityDamageByEntityEvent;

class Boat extends WaterAnimal implements Rideable,Damageable{
	const NETWORK_ID = 90;
	
	public $width = 0.95;
	public $length = 0.95;
	public $height = 0.455;
	
	public function initEntity(){
		$this->setMaxHealth(4);
		parent::initEntity();
	}

	public function getName(){
		return "Boat";
	}

	public function attack($damage, EntityDamageEvent $source){
		parent::attack($damage, $source);
		if($source->isCancelled()){
			return;
		}
		
		/*if($source instanceof EntityDamageByEntityEvent && $this->getHealth() > 0){
			$pk = new EntityEventPacket();
			$pk->eid = $this->getId();
			$pk->event = EntityEventPacket::HURT_ANIMATION;
			Server::broadcastPacket($this->hasSpawned, $pk);
		}
		elseif($source instanceof EntityDamageByEntityEvent && $this->getHealth() <= 0){
			$this->kill();
		}
		else{
			$this->kill();
		}*/
		$this->setHealth($this->getHealth() - $damage);
		if($this->getHealth()<=0){
			$this->kill();
		}
	}
	
	public function onUpdate($currentTick){
		return false;
	}

	public function spawnTo(Player $player){
		$pk = new AddEntityPacket();
		$pk->eid = $this->getId();
		$pk->type = Boat::NETWORK_ID;
		$pk->x = $this->x;
		$pk->y = $this->y;
		$pk->z = $this->z;
		$pk->speedX = 0;
		$pk->speedY = 0;
		$pk->speedZ = 0;
		$pk->yaw = 0;
		$pk->pitch = 0;
		$pk->metadata = $this->dataProperties;
		$player->dataPacket($pk);

		parent::spawnTo($player);
	}

	public function getDrops(){
		return [
			Item::get(Item::BOAT, $this->BoatType, 1)
		];
	}
}
