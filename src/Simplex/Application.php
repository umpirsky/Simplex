<?php

/*
* This file is part of the Simplex framework.
*
* (c) Саша Стаменковић <umpirsky@gmail.com>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/

namespace Simplex;

/**
 * The Simplex framework class.
 *
 * @author Саша Стаменковић <umpirsky@gmail.com>
 */
class Application extends \Silex\Application {
    
    /**
     * Application constructor.
     * 
     * @param array $options
     */
    public function __construct(array $options) {

        $app = $this;
        
        parent::__construct();
        
        // Register extensions
        $this->register(new \Silex\Provider\TwigServiceProvider(), array(
            'twig.path' => $options['twig.path'],
            'twig.class_path' => __DIR__ . '/../../vendor/silex/vendor/twig/lib',
        ));

        $this->register(new \Silex\Provider\UrlGeneratorServiceProvider());
        
        // Register error handlers
        $this->error(
            function (\Exception $e) use ($app) {
                if ($e instanceof NotFoundHttpException) {
                    return $app['twig']->render('error.html.twig', array('code' => 404));
                }

                $code = ($e instanceof HttpException) ? $e->getStatusCode() : 500;
                return $app['twig']->render('error.html.twig', array('code' => $code));
            }
        );
    }
    
    /**
     * Add menus navigation.
     *
     * @param array $navigation
     */
    public function addNavigation(array $navigation) {
        
        $app = $this;
        $this->register(new \Knp\Menu\Silex\KnpMenuServiceProvider());
        $menus = array();
        foreach ($navigation as $name => $menu) {
            $menuId = sprintf('simplex.navigation.%s', $name);
            $menus[$name] = $menuId;
            $this[$menuId] = function($app) use ($navigation, $name) {
                return $app['knp_menu.factory']->createFromArray($navigation[$name])
                        ->setCurrentUri($app['request']->getRequestUri());
            };
        }
        $this['knp_menu.menus'] = $menus;
        
    }
    
    /**
     * Add pages from navigation.
     *
     * @param array $navigation
     */
    public function addPages(array $navigation) {
        
        foreach ($navigation as $menu) {
            foreach ($menu['children'] as $item) {
                $this->addPage($item['route'], $item['uri'], $item['route']);
            }
        }
    }
 
    /**
     * Add page.
     *
     * @param string $route     route name
     * @param string $pattern   matched route pattern
     * @param string $view      view file name
     */
    public function addPage($route, $pattern, $view) {
        
        $app = $this;
        $this->get($pattern, function () use ($app, $view, $route) {
            return $app['twig']->render(sprintf('%s.html.twig', $view));
        })->bind($route);
    }
}
