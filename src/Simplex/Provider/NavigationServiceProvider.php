<?php

/*
 * This file is part of the Simplex framework.
 *
 * (c) Саша Стаменковић <umpirsky@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Simplex\Provider;

use Silex\Application;
use Silex\ServiceProviderInterface;

/**
 * Navigation provider.
 *
 * @author Саша Стаменковић <umpirsky@gmail.com>
 */
class NavigationServiceProvider implements ServiceProviderInterface {
    
    /**
     * @inheritdoc
     */
    public function register(Application $app) {
        
        $app->register(new \Knp\Menu\Silex\KnpMenuServiceProvider());
        $menus = array();
        foreach ($app['navigation'] as $name => $menu) {
            $menuId = sprintf('simplex.navigation.%s', $name);
            $menus[$name] = $menuId;
            $app[$menuId] = function($app) use ($menu) {
                return $app['knp_menu.factory']->createFromArray($menu)
                        ->setCurrentUri($app['request']->getRequestUri());
            };
        }
        $app['knp_menu.menus'] = $menus;
        
    }
}
