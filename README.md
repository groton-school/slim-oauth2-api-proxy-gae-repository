# groton-school/slim-oauth2-api-proxy-gae-repository

Firestore access token repository for Slim OAuth2 API proxy running on Google App Engine

[![Latest Version](https://img.shields.io/packagist/v/groton-school/slim-oauth2-api-proxy-gae-repository.svg)](https://packagist.org/packages/groton-school/slim-oauth2-api-proxy-gae-repository)

## Install

```bash
composer require groton-school/oauth2-api-proxy-gae-repository
```

## Use

This is an alternative to the default browser cookie storage for [groton-school/oauth2-api-proxy](https://github.com/groton-school/slim-oauth2-api-proxy#readme), with the advantage that when used in an embedded context (e.g. an LTI placement) with partitioned cookies, users will not need to reauthorize the API access nearly as frequently.

1. [Implement `AbstractUserIdentifierMiddleware`](https://github.com/groton-school/slim-skeleton/blob/df75c2ac2195f74994e9c5e1d5770fd7c2c6807e/src/Application/Middleware/ApiProxyUserIdentifier.php), creating a globally unique user identifier for any given request
2. [Configure `Firestore\AccessTokenRepository` for a given Provider in `dependencies.php`](https://github.com/groton-school/slim-skeleton/blob/df75c2ac2195f74994e9c5e1d5770fd7c2c6807e/app/dependencies.php#L70)
3. [Inject the `AbstractUserIdentifierMiddleware` implementation into the `RouteBuilder::define()` call](https://github.com/groton-school/slim-skeleton/blob/df75c2ac2195f74994e9c5e1d5770fd7c2c6807e/app/routes.php#L23-L28). (In the linked example, the `ApiProxyUserIdentifier` depends on data that must be provided by `Authenticated` first, and `PartitionedSession` is invoked last/outer because we are in an embedded LTI placement context).
