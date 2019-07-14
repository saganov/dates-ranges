<?php

namespace DateRange\Core;

use DateRange\Config\AppRoutes;
use ReflectionException;
use ReflectionMethod;
use ReflectionParameter;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;
use Symfony\Component\Routing\Matcher\UrlMatcher;
use Symfony\Component\Routing\RequestContext;

class Application
{
    /** @var AppRoutes */
    private $routes;

    private $services;

    /**
     * Application constructor.
     * @param AppRoutes $routes
     * @param ServiceLocator $services
     */
    public function __construct(AppRoutes $routes, ServiceLocator $services)
    {
        $this->routes = $routes;
        $this->services = $services;
    }

    /**
     * Application entry point
     */
    public function run()
    {
        $request = Request::createFromGlobals();
        $request->attributes->add(
            (new UrlMatcher(
                $this->routes->get(),
                (new RequestContext())->fromRequest($request))
            )->match($request->getPathInfo())
        );
        try {
            $response = call_user_func($this->controller($request), $this->arguments($request));
            $response->prepare($request)->send();
        } catch (ResourceNotFoundException $e) {
            echo $e->getMessage();
        } catch (ReflectionException $e) {
            echo $e->getMessage();
        }
    }

    /**
     * @param Request $request
     * @return Callable
     */
    private function controller(Request $request)
    {
        $controller = $request->get('_controller');
        return [new $controller($this->services), $request->get('_action')];
    }

    /**
     * @param Request $request
     * @return array
     * @throws ReflectionException
     */
    private function arguments(Request $request)
    {
        $reflectionMethod = new ReflectionMethod($request->get('_controller'), $request->get('_action'));
        $arguments = array();
        foreach ($reflectionMethod->getParameters() as $param) {
            /* @var $param ReflectionParameter */
            if ($request->attributes->has($param->getName())) {
                $arguments[] = $request->get($param->getName());
            } else {
                $arguments[] = $param->getDefaultValue();
            }
        }
        return $arguments;
    }
}
