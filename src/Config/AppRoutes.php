<?php

namespace DateRange\Config;

use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;

class AppRoutes
{
    /**
     * @return array
     */
    private function config()
    {
        return [
            '/api/v1' => [
                'get_ranges'   => ['GET',    '/ranges/',        'Ranges::list'],
                'save_range'   => ['PUT',    '/ranges/',        'Ranges::save'],
                'delete_range' => ['DELETE', '/ranges/{range}', 'Ranges::delete'],
            ]
        ];
    }

    /**
     * @return RouteCollection
     */
    public function get()
    {
        $routes = new RouteCollection();
        foreach ($this->config() as $prefix => $routesData) {
            $subRoutes = new RouteCollection();
            foreach ($routesData as $name => $route) {
                $subRoutes->add($name, $this->route($route[0], $route[1], $route[2]));
            }
            if ($prefix && $prefix !== '/') {
                $subRoutes->addPrefix($prefix);
            }
            $routes->addCollection($subRoutes);
        }
        return $routes;
    }

    /**
     * @param $method
     * @param $path
     * @param $controller
     * @return Route
     */
    private function route($method, $path, $controller)
    {
        list($controller, $action) = explode('::', $controller);
        return new Route(
            $path,
            ['_controller' => '\\DateRange\\Controllers\\'.$controller, '_action' => $action],
            [],
            [],
            null,
            [],
            [$method]
        );
    }
}