<?php declare(strict_types=1);

namespace V12\Http\Middleware;

use Psr\Http\Message\{ResponseInterface, ServerRequestInterface};
use Psr\Http\Middleware\{MiddlewareInterface, RequestHandlerInterface};

class RequestHandler implements RequestHandlerInterface{

  /**
   * @var MiddlewareInterface
   */
  private $middleware;

  /**
   * @var RequestHandlerInterface
   */
  private $next;

  /**
   * @param MiddlewareInterface $middleware
   * @param RequestHandlerInterface $next
   */
  public function __construct(MiddlewareInterface $middleware, RequestHandlerInterface $next){
    $this->middleware = $middleware;
    $this->next = $next;
  }

  /**
   * @param ServerRequestInterface $request
   * @return ResponseInterface
   */
  public function handle(ServerRequestInterface $request): ResponseInterface{
    return $this->middleware->handle($request, $this->next);
  }

}