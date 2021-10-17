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
		$posts = $repo->findThreadGoalHistory();
	}
	
    	if(!$options->apDgDisableThreadGoal)
	{
		$threads = $repo->findThreadGoalHistory();
	}
	
    	if(!$options->apDgDisableMemberGoal)
	{
		$members = $repo->findThreadGoalHistory();
	}
	
	$total['posts'] = isset($posts) ? $posts->count() : NULL;
	$total['threads'] = isset($threads) ? $threads->count() : NULL;
	$total['members'] = isset($members) ? $members->count() : NULL;
    
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
