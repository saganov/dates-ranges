<?php

namespace DateRange\Core;

use DateRange\Config\AppRoutes;
use ReflectionException;
use ReflectionMethod;
use ReflectionParameter;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;
use Symfony\Component\Routing\Matcher\UrlMatcher;
use Symfony\Component\Routing\RequestContext;

class Application
{
    /** @var AppRoutes */
    private $routes;

    /**
     * Application constructor.
     * @param AppRoutes $routes
     */
    public function __construct(AppRoutes $routes)
    {
        $this->routes = $routes;
    }

    /**
     * Application entry point
     */
    public function run()
    {
        $context = new RequestContext();
        $request = Request::createFromGlobals();
        $context->fromRequest($request);
        $matcher = new UrlMatcher($this->routes->get(), $context);
        try {
            $this->runAction($matcher->match($context->getPathInfo()))->prepare($request)->send();
        } catch (ResourceNotFoundException $e) {
            echo $e->getMessage();
        } catch (ReflectionException $e) {
            echo $e->getMessage();
        }
    }

    /**
     * @param $parameters
     * @return Response
     * @throws ReflectionException
     */
    private function runAction($parameters): Response
    {
        $controller = $parameters['_controller'];
        $action     = $parameters['_action'];
        $reflectionMethod = new ReflectionMethod($controller, $action);
        $pass = array();
        foreach($reflectionMethod->getParameters() as $param)
        {
            /* @var $param ReflectionParameter */
            if(isset($parameters[$param->getName()])) {
                $pass[] = $parameters[$param->getName()];
            } else {
                $pass[] = $param->getDefaultValue();
            }
        }
        $response = $reflectionMethod->invokeArgs(new $controller(), $pass);
        if (!is_a($response, Response::class)) {
            $response = new Response(json_encode($response, true));
        }
        return $response;
    }
}
