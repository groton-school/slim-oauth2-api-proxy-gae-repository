<?php

declare(strict_types=1);

namespace GrotonSchool\Slim\OAuth2\APIProxy\GAE;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

abstract class AbstractUserIdentifierMiddleware implements MiddlewareInterface
{
    public const USER_IDENTIFIER = self::class . '::user_identifier';

    public function process(
        ServerRequestInterface $request,
        RequestHandlerInterface $handler
    ): ResponseInterface {
        return $handler->handle(
            $request->withAttribute(
                self::USER_IDENTIFIER,
                $this->getIdentifier($request)
            )
        );
    }

    /**
     * @param ServerRequestInterface $request
     * @return string Globally unique user identifier
     */
    abstract protected function getIdentifier(ServerRequestInterface $request): string;
}
