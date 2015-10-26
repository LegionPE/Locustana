<?php

/*
 *
 *  ____            _        _   __  __ _                  __  __ ____
 * |  _ \ ___   ___| | _____| |_|  \/  (_)_ __   ___      |  \/  |  _ \
 * | |_) / _ \ / __| |/ / _ \ __| |\/| | | '_ \ / _ \_____| |\/| | |_) |
 * |  __/ (_) | (__|   <  __/ |_| |  | | | | | |  __/_____| |  | |  __/
 * |_|   \___/ \___|_|\_\___|\__|_|  |_|_|_| |_|\___|     |_|  |_|_|
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * @author PocketMine Team
 * @link http://www.pocketmine.net/
 *
 *
*/

/**
 * Common functions used to decode and encode packets for the Minecraft PE client.
 */
namespace pocketmine\utils;

use pocketmine\entity\Entity;
use raklib\Binary as RBinary;

class Binary extends RBinary{

	/**
	 * Writes a coded metadata string
	 *
	 * @param array $data
	 *
	 * @return string
	 */
	public static function writeMetadata(array $data){
		$m = "";
		foreach($data as $bottom => $d){
			$m .= chr(($d[0] << 5) | ($bottom & 0x1F));
			switch($d[0]){
				case Entity::DATA_TYPE_BYTE:
					$m .= self::writeByte($d[1]);
					break;
				case Entity::DATA_TYPE_SHORT:
					$m .= self::writeLShort($d[1]);
					break;
				case Entity::DATA_TYPE_INT:
					$m .= self::writeLInt($d[1]);
					break;
				case Entity::DATA_TYPE_FLOAT:
					$m .= self::writeLFloat($d[1]);
					break;
				case Entity::DATA_TYPE_STRING:
					$m .= self::writeLShort(strlen($d[1])) . $d[1];
					break;
				case Entity::DATA_TYPE_SLOT:
					$m .= self::writeLShort($d[1][0]);
					$m .= self::writeByte($d[1][1]);
					$m .= self::writeLShort($d[1][2]);
					break;
				case Entity::DATA_TYPE_POS:
					$m .= self::writeLInt($d[1][0]);
					$m .= self::writeLInt($d[1][1]);
					$m .= self::writeLInt($d[1][2]);
					break;
				case Entity::DATA_TYPE_LONG:
					$m .= self::writeLLong($d[1]);
					break;
			}
		}
		$m .= "\x7f";

		return $m;
	}

	/**
	 * Reads a metadata coded string
	 *
	 * @param      $value
	 * @param bool $types
	 *
	 * @return array
	 */
	public static function readMetadata($value, $types = false){
		$offset = 0;
		$m = [];
		$b = ord($value{$offset});
		++$offset;
		while($b !== 127 and isset($value{$offset})){
			$bottom = $b & 0x1F;
			$type = $b >> 5;
			switch($type){
				case Entity::DATA_TYPE_BYTE:
					$r = self::readByte($value{$offset});
					++$offset;
					break;
				case Entity::DATA_TYPE_SHORT:
					$r = self::readLShort(substr($value, $offset, 2));
					$offset += 2;
					break;
				case Entity::DATA_TYPE_INT:
					$r = self::readLInt(substr($value, $offset, 4));
					$offset += 4;
					break;
				case Entity::DATA_TYPE_FLOAT:
					$r = self::readLFloat(substr($value, $offset, 4));
					$offset += 4;
					break;
				case Entity::DATA_TYPE_STRING:
					$len = self::readLShort(substr($value, $offset, 2));
					$offset += 2;
					$r = substr($value, $offset, $len);
					$offset += $len;
					break;
				case Entity::DATA_TYPE_SLOT:
					$r = [];
					$r[] = self::readLShort(substr($value, $offset, 2));
					$offset += 2;
					$r[] = ord($value{$offset});
					++$offset;
					$r[] = self::readLShort(substr($value, $offset, 2));
					$offset += 2;
					break;
				case Entity::DATA_TYPE_POS:
					$r = [];
					for($i = 0; $i < 3; ++$i){
						$r[] = self::readLInt(substr($value, $offset, 4));
						$offset += 4;
					}
					break;
				case Entity::DATA_TYPE_LONG:
					$r = self::readLLong(substr($value, $offset, 4));
					$offset += 8;
					break;
				default:
					return [];
			}
			if($types === true){
				$m[$bottom] = [$r, $type];
			}else{
				$m[$bottom] = $r;
			}
			$b = ord($value{$offset});
			++$offset;
		}

		return $m;
	}

	public static function printFloat($value){
		return preg_replace("/(\\.\\d+?)0+$/", "$1", sprintf("%F", $value));
	}
}
