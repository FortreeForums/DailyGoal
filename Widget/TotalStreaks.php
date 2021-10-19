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

namespace apathy\DailyGoal\Widget;

use XF\Widget\AbstractWidget;

class TotalStreaks extends AbstractWidget
{
    public function render()
    {
    	$options = \XF::options();
    	$repo = $this->repository('apathy\DailyGoal:Streaks');
    	
    	if(!$options->apDgDisablePostGoal)
	{
		$posts = $this->finder('apathy\DailyGoal:History')
			      ->where('stats_type', 'post_goal')
			      ->fetch();
		
		$i = 0;
		$streak = 0;
		$postTotal = 0;
		$postGoalsMet = 0;
		
		foreach($posts as $goal)
		{
			if($goal['fulfilled'] && $i >= 1)
			{
				$streak++;
			}
			
			if($goal['fulfilled'] == 1)
			{
				$i++;
				$postGoalsMet++;
			}
			
			if($goal['fulfilled'] == 0)
			{
				$i = 0;
			}
			
			$postTotal++;
		}
		
		$posts = $streak;
	}
	
    	if(!$options->apDgDisableThreadGoal)
	{
		$threads = $this->finder('apathy\DailyGoal:History')
			        ->where('stats_type', 'thread_goal')
			        ->fetch();
			        
		$i = 0;
		$streak = 0;
		$threadTotal = 0;
		$threadGoalsMet = 0;
		
		foreach($threads as $goal)
		{		
			if($goal['fulfilled'] && $i >= 1)
			{
				$streak++;
			}
			
			if($goal['fulfilled'] == 1)
			{
				$i++;
				$threadGoalsMet++;
			}
			
			if($goal['fulfilled'] == 0)
			{
				$i = 0;
			}
			
			$threadTotal++;
		}
		
		$threads = $streak;
	}
	
    	if(!$options->apDgDisableMemberGoal)
	{
		$members = $this->finder('apathy\DailyGoal:History')
			        ->where('stats_type', 'member_goal')
			        ->fetch();
			        
		$i = 0;
		$streak = 0;
		$memberTotal = 0;
		$memberGoalsMet = 0;
		
		foreach($members as $goal)
		{		
			if($goal['fulfilled'] && $i >= 1)
			{
				$streak++;
			}
			
			if($goal['fulfilled'] == 1)
			{
				$i++;
				$memberGoalsMet++;
			}
			
			$memberTotal++;
		}
		
		$members = $streak;
	}
	
	$total['posts'] = isset($posts) ? $posts : NULL;
	$total['postTotal'] = isset($postTotal) ? $postTotal : NULL;
	$total['postGoalsMet'] = isset($postGoalsMet) ? $postGoalsMet : NULL;
	$total['threads'] = isset($threads) ? $threads : NULL;
	$total['threadTotal'] = isset($threadTotal) ? $threadTotal : NULL;
	$total['threadGoalsMet'] = isset($threadGoalsMet) ? $threadGoalsMet : NULL;
	$total['members'] = isset($members) ? $members : NULL;
	$total['memberTotal'] = isset($memberTotal) ? $memberTotal : NULL;
	$total['memberGoalsMet'] = isset($memberGoalsMet) ? $memberGoalsMet : NULL;
    
        $viewParams = [
        	'total' => $total
        ];

        return $this->renderer('ap_dg_total_streaks_widget', $viewParams);
    }

    public function getOptionsTemplate()
    {
	return '';
    }
}
