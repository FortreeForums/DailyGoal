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

namespace apathy\DailyGoal\Pub\Controller;

use XF\Mvc\Reply\View;

class Streaks extends \XF\Pub\Controller\AbstractController
{
	public static function getActivityDetails(array $activities)
	{
		return \XF::phrase('ap_dg_viewing_goal_streaks');	
	}
	
	public function actionIndex()
	{
		$options = \XF::options();
		$history = $this->finder('apathy\DailyGoal:History');
		$repo = $this->repository('apathy\DailyGoal:Stats');
		
		$page = $this->filterPage();
		$perPage = 15; // Maybe stop hardcoding this
		
		//$this->calculateStreakLengths($history);
		
		$viewParams = [
			'goal' => $history->fetch(),
			'longestStreak' => $this->calculateLongestStreak($history),
			'page' => $page,
			'perPage' => $perPage,
			'total' => $history->total()
		];

		return $this->view('apathy\DailyGoal:Streaks', 'ap_dg_streaks', $viewParams);
	}
	
	protected function calculateLongestStreak($entity)
	{	
		$streak = 0;
		$longest['count'] = 0;
		
		foreach($entity as $goal)
		{
			if($goal['fulfilled'] == 1)
			{
				if($streak == 0)
				{
					$longest['startDate'] = $goal['date'];
				}
				
				$streak++;
			}
			
			if($streak > $longest['count'])
			{
				$longest['count'] = $streak;
				$longest['endDate'] = $goal['date'];
			}
		}
		
		return $longest;
	}
	
	protected function calculateStreakLengths($entity)
	{
		// Do stuff
	}
}
