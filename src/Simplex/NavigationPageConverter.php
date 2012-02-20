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
 * Converts navigation to pages.
 *
 * @author Саша Стаменковић <umpirsky@gmail.com>
 */
class NavigationPageConverter {

    /**
     * Does the conversion.
     *
     * @param array $navigation
     * @return array of pages
     */
    public function convert(array $navigation) {

        $pages = array();

        foreach ($navigation as $menu) {
            $pages = array_merge($pages, $this->itemsToPages($menu['children']));
        }

        return $pages;
    }

    /**
     * Converts menu items to array of pages.
     *
     * @param array $items
     * @return array of pages
     */
    protected function itemsToPages($items) {

        $pages = array();
        foreach ($items as $item) {
            $pages[] = array(
                'route' => $item['route'],
                'uri' => $item['uri'],
                'view' => isset($item['view']) ? $item['view'] : $item['route']
            );
            if (isset($item['children'])) {
                $pages = array_merge($pages, $this->itemsToPages($item['children']));
            }
        }

        return $pages;
    }
}
