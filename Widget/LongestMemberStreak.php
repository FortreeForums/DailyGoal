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

class LongestMemberStreak extends AbstractWidget
{
    public function render()
    {
    	$options = \XF::options();
    	$repo = $this->repository('apathy\DailyGoal:Streaks');
    	
    	if(!$options->apDgDisableMemberGoal)
	{
		$members = $repo->findMemberGoalHistory();
		$longestMemberStreak = $repo->calculateLongestStreak($members);
	}
    
        $viewParams = [
        	'longestMemberStreak' => $longestMemberStreak
        ];

        return $this->renderer('ap_dg_member_streak_widget', $viewParams);
    }

    public function getOptionsTemplate()
    {
	return '';
    }
}
