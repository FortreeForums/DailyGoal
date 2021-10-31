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
		$timeframe = $options->apDgAutoAdjustTimeframePosts;
		
		foreach($posts as $goal)
		{
			if($goal['fulfilled'] && $i == $timeframe)
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
		$timeframe = $options->apDgAutoAdjustTimeframeThreads;
		
		foreach($threads as $goal)
		{		
			if($goal['fulfilled'] && $i == $timeframe)
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
		$timeframe = $options->apDgAutoAdjustTimeframeMembers;
		
		foreach($members as $goal)
		{		
			if($goal['fulfilled'] && $i == $timeframe)
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
	
	$total['posts'] = isset($posts) ? $posts : 0;
	$total['postTotal'] = isset($postTotal) ? $postTotal : 0;
	$total['postGoalsMet'] = isset($postGoalsMet) ? $postGoalsMet : 0;
	$total['threads'] = isset($threads) ? $threads : 0;
	$total['threadTotal'] = isset($threadTotal) ? $threadTotal : 0;
	$total['threadGoalsMet'] = isset($threadGoalsMet) ? $threadGoalsMet : 0;
	$total['members'] = isset($members) ? $members : 0;
	$total['memberTotal'] = isset($memberTotal) ? $memberTotal : 0;
	$total['memberGoalsMet'] = isset($memberGoalsMet) ? $memberGoalsMet : 0;
    
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
