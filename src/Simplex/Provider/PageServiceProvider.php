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
 * Page provider.
 *
 * @author Саша Стаменковић <umpirsky@gmail.com>
 */
class PageServiceProvider implements ServiceProviderInterface {
    
    /**
     * @inheritdoc
     */
    public function register(Application $app) {
        
        foreach ($app['navigation'] as $menu) {
            foreach ($menu['children'] as $item) {
                $route = $item['route'];
                $app->get($item['uri'], function () use ($app, $route) {
                    return $app['twig']->render(sprintf('%s.html.twig', $route));
                })->bind($route);
            }
        }
    }
}
