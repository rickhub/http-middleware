<?php declare(strict_types=1);

namespace V12\Http\Middleware;

use Psr\Http\Message\{ResponseInterface, ServerRequestInterface};
use Psr\Http\Middleware\{MiddlewareInterface, RequestHandlerInterface};

/**
 * Class MiddlewareFrame
 *
 * @package V12\Middleware\Server
 */
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
   * MiddlewareFrame constructor.
   *
   * @param MiddlewareInterface     $middleware
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