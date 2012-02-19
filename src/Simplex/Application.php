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
        
        if (!isset($options['simplex.application_path'])) {
            $options['simplex.application_path'] = __DIR__ . '/../../../../';
        }
        
        // Register extensions
        $this->register(new \Silex\Provider\TwigServiceProvider(), array(
            'twig.path' => $options['simplex.application_path'] . $options['twig.path'],
            'twig.class_path' => __DIR__ . '/../../vendor/silex/vendor/twig/lib'
        ));
        $this->register(new \Silex\Provider\UrlGeneratorServiceProvider());
        $this->register(new \Simplex\Provider\PageServiceProvider(), array(
            'navigation' => $options['navigation']
        ));
        $this->register(new \Simplex\Provider\NavigationServiceProvider(), array(
            'navigation' => $options['navigation']
        ));
        
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
}
