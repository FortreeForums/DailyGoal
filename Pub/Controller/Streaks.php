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
use XF\Pub\Controller\AbstractController;

class Streaks extends AbstractController
{
	public static function getActivityDetails(array $activities)
	{
		return \XF::phrase('ap_dg_viewing_goal_streaks');	
	}
	
	public function actionIndex()
	{
		$options = \XF::options();
		$repo = $this->repository('apathy\DailyGoal:Streaks');
		$visitor = \XF::visitor();
		
		if(!$visitor->hasPermission('ap_dailygoals', 'ap_view_goal_streaks'))
		{
			return $this->noPermission();
		}
		
		$page = $this->filterPage();
		$perPage = $options->apDgStreakPageLimit;
		
		$history = $this->finder('apathy\DailyGoal:History');
		$streakLengths = $this->drawStreakGraph($history->fetch());
		
		$goal = $history->limitByPage($page, $perPage)->order('date', 'desc')->fetch();
		
		$streakTypes = [
			'post' => 'Post streak',
			'thread' => 'Thread streak',
			'member' => 'Member streak'
		];
		
		$viewParams = [
			'goal' => $goal,
			'streakLengths' => $streakLengths,
			'streakTypes' => $streakTypes,
			'page' => $page,
			'perPage' => $perPage,
			'total' => $history->total()
		];

		return $this->view('apathy\DailyGoal:Streaks', 'ap_dg_streaks', $viewParams);
	}
	
	protected function drawStreakGraph($entity)
	{
		$streak = 0;
		$options = \XF::options();
		
		$streaks[] = '';
		
		foreach($entity as $goal)
		{
			$date = date('Y-m-d', $goal['date']);
			
			if($goal['fulfilled'] == 1)
			{
				$streak++;
			}
			elseif($goal['fulfilled'] == 0)
			{
				$streak = 0;
			}
			
			if($goal['stats_type'] == 'post_goal'
			&& !$options->apDgDisablePostGoal)
			{
				$values['post'] = $streak;
				$averages['post'] = $streak;
			}
			
			if($goal['stats_type'] == 'thread_goal'
			&& !$options->apDgDisableThreadGoal)
			{
				$values['thread'] = $streak;
				$averages['thread'] = $streak;
			}
			
			if($goal['stats_type'] == 'member_goal'
			&& !$options->apDgDisableMemberGoal)
			{
				$values['member'] = $streak;
				$averages['member'] = $streak;
			}
			
			$streaks[$date] = [
				'ts' => $goal['date'],
				'label' => date('M j, Y', $goal['date']),
				'days' => 1,
				'count' => 1,
				'values' => $values,
				'averages' => $averages,
			];
		}
		
		unset($streaks[0]);
		
		return $streaks;
	}
}
