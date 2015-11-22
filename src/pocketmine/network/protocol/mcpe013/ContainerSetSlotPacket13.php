<?php

/*
 * Locustana
 *
 * Copyright (C) 2015 PEMapModder
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * @author PEMapModder
 */

namespace pocketmine\network\protocol\mcpe013;

use pocketmine\network\protocol\ContainerSetSlotPacket;

class ContainerSetSlotPacket13 extends ContainerSetSlotPacket{
	public $hotbarSlot;

	public function decode(){
		$this->windowid = $this->getByte();
		$this->slot = $this->getShort();
		$this->hotbarSlot = $this->getShort();
		$this->item = $this->getSlot();
	}

	public function encode(){
		$this->reset();
		$this->putByte($this->windowid);
		$this->putShort($this->slot);
		$this->putShort($this->hotbarSlot);
		$this->putSlot($this->item);
	}
}
