<?php

declare(strict_types=1);

namespace Resonance;

use Resonance\Attribute\Singleton;
use Swoole\Http\Request;

#[Singleton]
readonly class TwigFilterTrans
{
    public function __construct(private Translator $translator) {}

    public function __invoke(string $message, Request $request): string
    {
        return $this->translator->trans($request, $message);
    }
}
