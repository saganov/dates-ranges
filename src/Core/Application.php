<?php

namespace DateRange\Core;

use DateRange\Config\AppRoutes;
use DateRange\Core\Request as RequestCore;
use Exception;
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
        try {
            $request->attributes->add(
                (new UrlMatcher(
                    $this->routes->get(),
                    (new RequestContext())->fromRequest($request))
                )->match($request->getPathInfo())
            );
            /** @var Response $response */
            $response = call_user_func_array($this->controller($request), $this->arguments($request));
            $response->prepare($request)->send();
        } catch (ResourceNotFoundException $e) {
            $this->error($e->getMessage(), Response::HTTP_NOT_FOUND)->send();
        } catch (InvalidRequestException $e) {
            $this->error($e->getMessage(), Response::HTTP_BAD_REQUEST)->send();
        } catch (ReflectionException $e) {
            $this->error($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR)->send();
        } catch (Exception $e) {
            $this->error($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR)->send();
        }
    }

    /**
     * @param string $message
     * @param int $status
     * @return Response
     */
    private function error(string $message, int $status): Response
    {
        return new Response(
            json_encode(['message' => $message]),
            $status,
            ['Content-Type', 'application/json;charset=UTF-8']
        );
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
     * @throws InvalidRequestException
     * @throws ReflectionException
     */
    private function arguments(Request $request)
    {
        $reflectionMethod = new ReflectionMethod($request->get('_controller'), $request->get('_action'));
        $arguments = array();
        foreach ($reflectionMethod->getParameters() as $param) {
            /* @var ReflectionParameter $param */
            $class = $param->getClass();
            if ($class && $class->implementsInterface(RequestCore::class)) {
                /** @var RequestCore $validator */
                $validator = $class->newInstance(json_decode($request->getContent(), true));
                $validator->validate();
                $arguments[] = $validator;
            } elseif ($request->attributes->has($param->getName())) {
                $arguments[] = $request->get($param->getName());
            } else {
                $arguments[] = $param->getDefaultValue();
            }
        }
        return $arguments;
    }
}
