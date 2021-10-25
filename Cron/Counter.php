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

namespace apathy\DailyGoal\Cron;

class Counter
{
	public static function countPostsFromToday()
	{
		$options = \XF::options();
		$forum_id = $options->apDgExcludedNodesPosts;

		if(empty($forum_id))
		{
			$forum_id = '0';
		}

		if(!$options->apDgDisablePostGoal)
		{
			$app = \XF::app();
			$finder = \XF::finder('XF:Post');
                    	$post_date = 'DATE(FROM_UNIXTIME(xf_post.post_date)) = CURDATE()';
                    	
                    	$postCount = $finder->with('Thread')
                    			    ->whereSql($post_date)
                    			    ->where('Thread.node_id', '!=', $forum_id)
                    			    ->fetch()
                    			    ->count();
                    				
                    	/* Check if [UW] Forum Comments System is installed */
                    	$addons = \XF::app()->container('addon.cache');
                    	
                    	if(array_key_exists('UW/FCS', $addons) 
			&& $addons['UW/FCS'] >= 1
			&& $options->apDgIncludeComments)
			{
                    		$finder = \XF::finder('UW\FCS:Comment');
                    		$comment_date = 'DATE(FROM_UNIXTIME(comment_date)) = CURDATE()';
                    		
                    		$commentCount = $finder->with('Thread')
                    				       ->whereSql($comment_date)
                    				       ->where('Thread.node_id', '!=', $forum_id)
                    				       ->fetch()
                    				       ->count();
                    				       
                    		$count = ($postCount + $commentCount);
                    	}
                    	else
                    	{
                    		$count = $postCount;
                    	}
                    	
			$simpleCache = $app->simpleCache();
			$simpleCache['apathy/DailyGoal']['count'] = $count;
		}
	}

	public static function countThreadsFromToday()
	{
		$options = \XF::options();
		$forum_id = $options->apDgExcludedNodesThreads;

		if(empty($forum_id))
		{
			$forum_id = '0';
		}

		if(!$options->apDgDisableThreadGoal)
		{         
			$app = \XF::app();           			
                    	$finder = \XF::finder('XF:Thread');
                    	$post_date = 'DATE(FROM_UNIXTIME(post_date)) = CURDATE()';
                    	
                    	$count = $finder->whereSql($post_date)
                    			->where('node_id', '!=', $forum_id)
                    			->fetch()
                    			->count();

			$simpleCache = $app->simpleCache();
			$simpleCache['apathy/DailyGoal']['threadCount'] = $count;
		}
	}

	public static function countMembersFromToday()
	{
		$options = \XF::options();

		if(!$options->apDgDisableMemberGoal)
		{	
			$app = \XF::app();			
			$finder = \XF::finder('XF:User');
                    	$register_date = 'DATE(FROM_UNIXTIME(register_date)) = CURDATE()';
                    	
                    	$count = $finder->whereSql($register_date)
                    			->fetch()
                    			->count();

			$simpleCache = $app->simpleCache();
			$simpleCache['apathy/DailyGoal']['memberCount'] = $count;
		}
	}

	public static function resetCounterAtMidnight()
	{
		$app = \XF::app();
		$db = \XF::db();
		$options = \XF::options();

		$simpleCache = $app->simpleCache();
		
		if(!$options->apDgDisablePostGoal)
		{
			// Submit the total to xf_ap_daily_goal_history
			$total = $simpleCache['apathy/DailyGoal']['count'];
			
			if($total >= $options->apDgPostGoal)
			{
				$fulfilled = 1;
			}
			else
			{
				$fulfilled = 0;
			}
			
			$goal = $options->apDgPostGoal;
			
			$db->query('INSERT INTO xf_ap_daily_goal_history
				    VALUES (?, ?, ?, ?, ?, ?)',
				    [NULL, \XF::$time, 'post_goal', $total, $goal, $fulfilled]);
				    
			// Reset the cache
			$simpleCache['apathy/DailyGoal']['count'] = 0;
			
			if(!$options->apDgDiableAutoAdjustment)
			{			
				// Auto-adjust goal if needed
				$postTimeframe = $options->apDgAutoAdjustTimeframePosts;
				$postWeight = $options->apDgAutoAdjustWeightPosts;
			
				$postFinder = \XF::finder('apathy\DailyGoal:History');
				$postResult = $postFinder->where('stats_type', 'post_goal')->fetch();
			
				$streak = 0;
			
				foreach($postResult as $goal)
				{
					if($goal['fulfilled'] == 1)
					{
						$streak++;
					}

					if($goal['fulfilled'] == 0)
					{
						$streak = 0;
					}
				}
			
				$option = \XF::em()->find('XF:Option', 'apDgPostGoal');
				
				if($goal['fulfilled'] == 1 && $streak >= $postTimeframe)
				{
					$option->option_value = ( $options->apDgPostGoal + $postWeight );
				}
				
				elseif($goal['fulfilled'] == 0 && $streak <= $postTimeframe)
				{
					$option->option_value = ( $options->apDgPostGoal - $postWeight );
				}
			
				$option->save();
			}
		}
		
		if(!$options->apDgDisableThreadGoal)
		{
			// Submit the total to xf_ap_daily_goal_history
			$total = $simpleCache['apathy/DailyGoal']['threadCount'];
			
			if($total >= $options->apDgThreadGoal)
			{
				$fulfilled = 1;
			}
			else
			{
				$fulfilled = 0;
			}
			
			$goal = $options->apDgThreadGoal;
			
			$db->query('INSERT INTO xf_ap_daily_goal_history
				    VALUES (?, ?, ?, ?, ?, ?)',
				    [NULL, \XF::$time, 'thread_goal', $total, $goal, $fulfilled]);
				
			// Reset the cache    
			$simpleCache['apathy/DailyGoal']['threadCount'] = 0;
			
			if(!$options->apDgDiableAutoAdjustment)
			{
				// Auto-adjust goal if needed
				$threadTimeframe = $options->apDgAutoAdjustTimeframeThreads;
				$threadWeight = $options->apDgAutoAdjustWeightThreads;
				
				$threadFinder = \XF::finder('apathy\DailyGoal:History');
				$threadResult = $threadFinder->where('stats_type', 'thread_goal')->fetch();
				$streak = 0;

				foreach($threadResult as $goal)
				{
					if($goal['fulfilled'] == 1)
					{
						$streak++;
					}
				
					if($goal['fulfilled'] == 0)
					{
						$streak = 0;
					}
				}
			
				$option = \XF::em()->find('XF:Option', 'apDgThreadGoal');
				
				if($goal['fulfilled'] == 1 && $streak >= $threadTimeframe)
				{
					$option->option_value = ( $options->apDgThreadGoal + $threadWeight );
				}
				
				elseif($goal['fulfilled'] == 0 && $streak <= $threadTimeframe)
				{
					$option->option_value = ( $options->apDgThreadGoal - $threadWeight );
				}
			
				$option->save();
			}
		}
		
		if(!$options->apDgDisableMemberGoal)
		{			
			// Submit the total to xf_ap_daily_goal_history
			$total = $simpleCache['apathy/DailyGoal']['memberCount'];
			
			if($total >= $options->apDgMemberGoal)
			{
				$fulfilled = 1;
			}
			else
			{
				$fulfilled = 0;
			}
			
			$goal = $options->apDgMemberGoal;
			
			$db->query('INSERT INTO xf_ap_daily_goal_history
				    VALUES (?, ?, ?, ?, ?, ?)',
				    [NULL, \XF::$time, 'member_goal', $total, $goal, $fulfilled]);
			
			// Reset the cache	    
			$simpleCache['apathy/DailyGoal']['memberCount'] = 0;
			
			if(!$options->apDgDiableAutoAdjustment)
			{
				// Auto-adjust goal if needed
				$memberTimeframe = $options->apDgAutoAdjustTimeframeMembers;
				$memberWeight = $options->apDgAutoAdjustWeightMembers;
			
				$memberFinder = \XF::finder('apathy\DailyGoal:History');
				$memberResult = $memberFinder->where('stats_type', 'member_goal')->fetch();
				$streak = 0;
			
				foreach($memberResult as $goal)
				{
					if($goal['fulfilled'] == 1)
					{
						$streak++;
					}
				
					if($goal['fulfilled'] == 0)
					{
						$streak = 0;
					}
				}
			
				$option = \XF::em()->find('XF:Option', 'apDgMemberGoal');
				
				if($goal['fulfilled'] == 1 && $streak >= $memberTimeframe)
				{
					$option->option_value = ( $options->apDgMemberGoal + $memberWeight );
				}
				elseif($goal['fulfilled'] == 0 && $streak <= $memberTimeframe)
				{
					$option->option_value = ( $options->apDgMemberGoal - $memberWeight );
				}
			
				$option->save();
			}
		}
	}
}
