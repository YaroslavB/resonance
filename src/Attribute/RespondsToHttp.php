<?php

declare(strict_types=1);

namespace Distantmagic\Resonance\Attribute;

use Attribute;
use Distantmagic\Resonance\Attribute as BaseAttribute;
use Distantmagic\Resonance\HttpRouteSymbolInterface;
use Distantmagic\Resonance\RequestMethod;

#[Attribute(Attribute::TARGET_CLASS)]
final readonly class RespondsToHttp extends BaseAttribute
{
    public function __construct(
        public RequestMethod $method,
        public string $pattern,
        public HttpRouteSymbolInterface $routeSymbol,
        public int $priority = 0,
        public array $requirements = [],
    ) {}
}
