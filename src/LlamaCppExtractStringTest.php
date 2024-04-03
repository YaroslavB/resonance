<?php

declare(strict_types=1);

namespace Distantmagic\Resonance;

use Generator;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\TestCase;
use Swoole\Event;

/**
 * @internal
 */
#[CoversClass(LlamaCppExtractString::class)]
#[Group('llamacpp')]
final class LlamaCppExtractStringTest extends TestCase
{
    use TestsDependencyInectionContainerTrait;

    public static function inputSubjectProvider(): Generator
    {
        yield 'application name is provided' => [
            'application name',
            'My application is called PHP Resonance',
            'PHP Resonance',
        ];

        yield 'only application name is provided' => [
            'application name',
            'PHP Resonance',
            'PHP Resonance',
        ];

        yield 'application name is not provided' => [
            'application name',
            'How are you?',
            null,
        ];

        yield 'not on topic' => [
            'application name',
            'Suggest me the best application name',
            null,
        ];

        yield 'not sure' => [
            'application name',
            'I am not really sure at the moment, was thinking about PHP Resonance, but I have to ask my friends first',
            null,
        ];

        yield 'feature' => [
            'feature',
            'I want to add a blog',
            'blog',
        ];
    }

    protected function tearDown(): void
    {
        Event::wait();
    }

    #[DataProvider('inputSubjectProvider')]
    public function test_application_name_is_provided(string $subject, string $input, ?string $expected): void
    {
        $llamaCppExtractString = self::$container->make(LlamaCppExtractString::class);

        SwooleCoroutineHelper::mustRun(static function () use ($expected, $input, $llamaCppExtractString, $subject) {
            $extracted = $llamaCppExtractString->extract(
                subject: $subject,
                input: $input,
            );

            self::assertSame($expected, $extracted);
        });
    }
}
