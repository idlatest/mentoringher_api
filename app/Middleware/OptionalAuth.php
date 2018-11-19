<?php

namespace App\Middleware;

use Interop\Container\ContainerInterface;
use Slim\DeferredCallable;

class OptionalAuth
{
    private $container;
   
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function __invoke($request, $response, $next)
    {
        if ($request->hasHeader('HTTP_AUTHORIZATION') && $request->getHeader('HTTP_AUTHORIZATION')[0] != '' && $request->getHeader('HTTP_AUTHORIZATION')[0] != null) {
            $callable = new DeferredCallable($this->container->get('jwt'), $this->container);
            return call_user_func($callable, $request, $response, $next);
        }
        return $next($request, $response);
    }
}
