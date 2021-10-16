<?php

//  ▄▄▄·  ▄▄▄· ▄▄▄· ▄▄▄▄▄ ▄ .▄ ▄· ▄▌
// ▐█ ▀█ ▐█ ▄█▐█ ▀█ •██  ██▪▐█▐█▪██▌
// ▄█▀▀█  ██▀·▄█▀▀█  ▐█.▪██▀▐█▐█▌▐█▪
// ▐█ ▪▐▌▐█▪·•▐█ ▪▐▌ ▐█▌·██▌▐▀ ▐█▀·.
//  ▀  ▀ .▀    ▀  ▀  ▀▀▀ ▀▀▀ ·  ▀ •
//  https://fortreeforums.xyz
//  Licensed under GPL-3.0-or-later 2021
//
//  This file is part of [AP] Daily Goals ("Daily Goals").
//
//  Daily Goals is free software: you can redistribute it and/or modify
//  it under the terms of the GNU General Public License as published by
//  the Free Software Foundation, either version 3 of the License, or
//  (at your option) any later version.
//
//  Daily Goals is distributed in the hope that it will be useful,
//  but WITHOUT ANY WARRANTY; without even the implied warranty of
//  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
//  GNU General Public License for more details.
//
//  You should have received a copy of the GNU General Public License
//  along with Daily Goals.  If not, see <https://www.gnu.org/licenses/>.

namespace apathy\DailyGoal\Entity;

use XF\Mvc\Entity\Structure;
use \XF\Mvc\Entity\Entity;

class History extends Entity
{
	public static function getStructure(Structure $structure)
	{
		$structure->table = 'xf_ap_daily_goal_history';
    		$structure->shortName = 'apathy\DailyGoal:History';
    		$structure->primaryKey = 'date';
    		$structure->columns = [
        		'date' => ['type' => self::UINT, 'default' => \XF::$time],
        		'stats_type' => ['type' => self::STR, 'maxLength' => 30, 'default' => false],
			'counter' => ['type' => self::UINT, 'default' => 0],
			'fulfilled' => ['type' => self::BOOL, 'default' => 0]
    			];
    		$structure->getters = [];
    		$structure->relations = [];

    		return $structure;
	}
}
