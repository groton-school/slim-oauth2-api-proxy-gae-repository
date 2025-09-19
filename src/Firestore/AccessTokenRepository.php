<?php

declare(strict_types=1);

namespace GrotonSchool\Slim\OAuth2\APIProxy\GAE\Firestore;

use Exception;
use Google\Cloud\Firestore\FirestoreClient;
use GrotonSchool\Slim\OAuth2\APIProxy\Domain\AccessToken\AbstractAccessTokenRepository;
use GrotonSchool\Slim\OAuth2\APIProxy\Domain\Provider\ProviderInterface;
use GrotonSchool\Slim\OAuth2\APIProxy\GAE\AbstractUserIdentifierMiddleware;
use League\OAuth2\Client\Token\AccessToken;
use Psr\Http\Message\ResponseInterface;
use Slim\Http\ServerRequest;

class AccessTokenRepository extends AbstractAccessTokenRepository
{
    private FirestoreClient $firestore;

    public function __construct(private ProviderInterface $provider)
    {
        parent::__construct($this->provider);
        $this->firestore = new FirestoreClient();
    }

    private function getLocator(string $userId): string
    {
        return  "api_proxy/{$this->provider->getSlug()}/tokens/$userId";
    }


    public function getToken(ServerRequest $request): ?AccessToken
    {
        try {
            $document = $this->firestore->document(
                $this->getLocator(
                    $request->getAttribute(
                        AbstractUserIdentifierMiddleware::USER_IDENTIFIER
                    )
                )
            );
            $snapshot = $document->snapshot();
            if ($snapshot->exists()) {
                return new AccessToken($snapshot->data());
            }
        } catch (Exception $_) {
            // do nothing
        }
        return null;
    }

    public function setToken(
        AccessToken $token,
        ServerRequest $request,
        ResponseInterface $response
    ): ResponseInterface {
        $document = $this->firestore->document(
            $this->getLocator(
                $request->getAttribute(
                    AbstractUserIdentifierMiddleware::USER_IDENTIFIER
                )
            )
        );
        $document->set($token->jsonSerialize());
        return $response;
    }

    public function deleteToken(
        ServerRequest $request,
        ResponseInterface $response
    ): ResponseInterface {
        $document = $this->firestore->document(
            $this->getLocator(
                $request->getAttribute(
                    AbstractUserIdentifierMiddleware::USER_IDENTIFIER
                )
            )
        );
        $document->delete();
        return $response;
    }
}
