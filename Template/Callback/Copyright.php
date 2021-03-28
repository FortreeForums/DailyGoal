<?php

namespace apathy\DailyGoal\Template\Callback;

class Copyright
{
    public static function getCopyrightText()
    {
        $app = \XF::app();

        $branding = $app->offsetExists('apathy_branding') ? $app->apathy_branding : [];

        if (!count($branding) OR !is_array($branding))
        {
            return '';
        }

        $html = '<div>
			Some of the sites addons were developed by <a class="u-concealed" rel="nofollow noopener" href="https://fortreeforums.xyz/" target="_blank">Fortree Treehouses</a>
		</div>';

        $app->apathy_branding = [];

        return $html;
    }
}