<?php declare(strict_types=1);

namespace V12\Http\Middleware;

use Psr\Http\Message\{ResponseInterface, ServerRequestInterface};
use Psr\Http\Middleware\{MiddlewareInterface, RequestHandlerInterface};

class MiddlewareQueue{

  /**
   * @var MiddlewareInterface[]
   */
  private $middlewares = [];

  /**
   * @param MiddlewareInterface $middleware
   * @return MiddlewareQueue
   */
  public function add(MiddlewareInterface $middleware): MiddlewareQueue{
    $this->middlewares[] = $middleware;
    return $this;
  }

  /**
   * @param  ServerRequestInterface $request
   * @param  ResponseInterface $response
   * @return ResponseInterface
   */
  public function handle(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface{
    $stack = $this->createStack($response);
    return $stack->handle($request);
  }

  /**
   * @param  ResponseInterface $response
   * @return RequestHandlerInterface
   */
  protected function createStack(ResponseInterface $response): RequestHandlerInterface{
    return array_reduce(array_reverse($this->middlewares), function(
      RequestHandlerInterface $frame,
      MiddlewareInterface $middleware
    ): RequestHandlerInterface{
      return new RequestHandler($middleware, $frame);
    }, $this->createLastHandler($response));
  }

  /**
   * @param ResponseInterface $response
   * @return RequestHandlerInterface
   */
  protected function createLastHandler(ResponseInterface $response): RequestHandlerInterface{
    return new class($response) implements RequestHandlerInterface{

      /**
       * @var ResponseInterface
       */
      private $response;

      /**
       * @param ResponseInterface $response
       */
      public function __construct(ResponseInterface $response){
        $this->response = $response;
      }

      /**
       * @param ServerRequestInterface $request
       * @return ResponseInterface
       */
      public function handle(ServerRequestInterface $request): ResponseInterface{
        return $this->response;
      }

    };
  }

}
