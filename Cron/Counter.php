<?php

namespace apathy\DailyGoal\Cron;

class Counter
{
	public static function countPostsFromToday()
	{
		$db = \XF::db();
		$app = \XF::app();
		$options = \XF::options();
		$forums = $options->ap_post_goal_forums;
		$forum_id = implode(",", $forums);

		if(!$options->ap_disable_post_goal && !empty($forum_id))
		{
			$cache = $db->fetchOne('SELECT count(p.post_id) AS count 
						FROM xf_post AS p
                    				LEFT JOIN xf_thread AS t ON (t.thread_id = p.thread_id)
                    				LEFT JOIN xf_forum AS f ON (f.node_id = t.node_id)
						WHERE DATE(FROM_UNIXTIME(p.post_date)) = CURDATE()
                    				AND f.node_id IN ('.$forum_id.')');

			$simpleCache = $app->simpleCache();
			$simpleCache['apathy/DailyGoal']['count'] = $cache;
		}
	}

	public static function countThreadsFromToday()
	{
		$db = \XF::db();
		$app = \XF::app();
		$options = \XF::options();
		$forums = $options->ap_thread_goal_forums;
		$forum_id = implode(",", $forums);

		if(!$options->ap_disable_thread_goal && !empty($forum_id))
		{
			$cache = $db->fetchOne('SELECT count(thread_id) AS threadCount
						FROM xf_thread
						WHERE DATE(FROM_UNIXTIME(post_date)) = CURDATE()
                    				AND node_id IN ('.$forum_id.')');

			$simpleCache = $app->simpleCache();
			$simpleCache['apathy/DailyGoal']['threadCount'] = $cache;
		}
	}

	public static function countMembersFromToday()
	{
		$db = \XF::db();
		$app = \XF::app();
		$options = \XF::options();

		if(!$options->ap_disable_member_goal)
		{
			$cache = $db->fetchOne('SELECT count(user_id) AS memberCount
						FROM xf_user
						WHERE DATE(FROM_UNIXTIME(register_date)) = CURDATE()');

			$simpleCache = $app->simpleCache();
			$simpleCache['apathy/DailyGoal']['memberCount'] = $cache;
		}
	}

	public static function resetCounterAtMidnight()
	{
		$app = \XF::app();
		$options = \XF::options();

		$simpleCache = $app->simpleCache();

		if(!$options->ap_disable_post_goal)
		{
			$simpleCache['apathy/DailyGoal']['count'] = 0;
		}
		if(!$options->ap_disable_thread_goal)
		{
			$simpleCache['apathy/DailyGoal']['threadCount'] = 0;
		}
		if(!$options->ap_disable_member_goal)
		{
			$simpleCache['apathy/DailyGoal']['memberCount'] = 0;
		}
	}
}