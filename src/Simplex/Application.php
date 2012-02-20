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

        parent::__construct();

        foreach ($options as $k => $v) {
            $app[$k] = $v;
        }
    }
}
