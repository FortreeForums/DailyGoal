<?php

namespace apathy\DailyGoal\Widget;

use XF\Widget\AbstractWidget;

class GoalCounters extends AbstractWidget
{
    public function render()
    {
        $viewParams = [];

        return $this->renderer('ap_daily_goal_widget', $viewParams);
    }

    public function getOptionsTemplate()
    {
	return '';
    }
}