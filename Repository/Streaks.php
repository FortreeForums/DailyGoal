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

namespace apathy\DailyGoal\Repository;

use XF\Mvc\Entity\Finder;
use XF\Mvc\Entity\Repository;

class Streaks extends Repository
{
	public function findPostGoalHistory()
	{
		$finder = $this->finder('apathy\DailyGoal:History');
		$result = $finder->where('stats_type', 'post_goal')->fetch();
		
		return $result;
	}
	
	public function findThreadGoalHistory()
	{
		$finder = $this->finder('apathy\DailyGoal:History');
		$result = $finder->where('stats_type', 'thread_goal')->fetch();
		
		return $result;
	}
	
	public function findMemberGoalHistory()
	{
		$finder = $this->finder('apathy\DailyGoal:History');
		$result = $finder->where('stats_type', 'member_goal')->fetch();
		
		return $result;
	}
	
	public function calculateLongestStreak($entity)
	{	
		$streak = 0;
		$longest['count'] = 0;
		$options = \XF::options();
		
		foreach($entity as $goal)
		{	
			if($goal['fulfilled'] == 1)
			{	
				if($streak == 0
				&& $streak >= $longest['count'])
				{
					$longest['startDate'] = $goal['date'];
				}

				$streak++;
			}
			
			if($streak > $longest['count'])
			{
				$longest['count'] = $streak;
			}
			
			if($goal['fulfilled'] == 0 && $streak == $longest['count'])
			{
				$longest['endDate'] = $goal['date'];
				$streak = 0;
			}
		}
		
		return $longest;
	}
}
