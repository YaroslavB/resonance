<?php

declare(strict_types=1);

namespace Distantmagic\Resonance\HttpMiddleware;

use Distantmagic\Resonance\Attribute;
use Distantmagic\Resonance\Attribute\ContentSecurityPolicy;
use Distantmagic\Resonance\Attribute\HandlesMiddlewareAttribute;
use Distantmagic\Resonance\Attribute\Singleton;
use Distantmagic\Resonance\ContentSecurityPolicyType;
use Distantmagic\Resonance\HttpInterceptableInterface;
use Distantmagic\Resonance\HttpMiddleware;
use Distantmagic\Resonance\HttpResponderInterface;
use Distantmagic\Resonance\SecurityPolicyHeaders;
use Distantmagic\Resonance\SingletonCollection;
use Psr\Http\Message\ServerRequestInterface;
use Swoole\Http\Response;

/**
 * @template-extends HttpMiddleware<ContentSecurityPolicy>
 */
#[HandlesMiddlewareAttribute(
    attribute: ContentSecurityPolicy::class,
    priority: 900,
)]
#[Singleton(collection: SingletonCollection::HttpMiddleware)]
readonly class ContentSecurityPolicyMiddleware extends HttpMiddleware
{
    public function __construct(
        private SecurityPolicyHeaders $securityPolicyHeaders,
    ) {}

    public function preprocess(
        ServerRequestInterface $request,
        Response $response,
        Attribute $attribute,
        HttpInterceptableInterface|HttpResponderInterface $next,
    ): HttpInterceptableInterface|HttpResponderInterface {
        match ($attribute->contentSecurityPolicyType) {
            ContentSecurityPolicyType::Html => $this->securityPolicyHeaders->sendTemplatedPagePolicyHeaders($request, $response),
            ContentSecurityPolicyType::Json => $this->securityPolicyHeaders->sendJsonPagePolicyHeaders($response),
        };

        return $next;
    }
}
