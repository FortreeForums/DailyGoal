<?php

namespace apathy\DailyGoal;

use XF\Mvc\Entity\Entity;

class Listener
{
    protected static $_productId = 2;

    public static function appPubSetup(\XF\App $app)
    {
        $branding = $app->offsetExists('apathy_branding') ? $app->apathy_branding : [];

        $branding[] = self::$_productId;

        $app->apathy_branding = $branding;
    }
}