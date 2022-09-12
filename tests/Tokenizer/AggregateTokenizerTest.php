<?php

declare(strict_types=1);

namespace Tests\Tokenizer;

use Major\TokenDumper\Tokenizer as T;
use PHPUnit\Framework\TestCase;

final class AggregateTokenizerTest extends TestCase
{
    private T\AggregateTokenizer $tokenizer;

    protected function setUp(): void
    {
        $this->tokenizer = new T\AggregateTokenizer([
            new T\NativeTokenizer(),
            new T\PhpCsFixerTokenizer(),
        ]);
    }

    public function testSimple(): void
    {
        $tokens = $this->tokenizer->tokenize("<?php echo 'Hello!';\n");

        self::assertCount(6, $tokens);

        self::assertSame(['T_OPEN_TAG', 'T_OPEN_TAG'], $tokens[0]->getNames());
        self::assertSame('<?php ', $tokens[0]->getContent());

        self::assertSame([null, null], $tokens[4]->getNames());
        self::assertSame(';', $tokens[4]->getContent());
    }

    public function testTransformed(): void
    {
        $tokens = $this->tokenizer->tokenize(
            "<?php fn (array \$a) => \$a;\n[\$a, \$b] = [1, 2];\n",
        );

        self::assertCount(31, $tokens);

        $expected = ['T_ARRAY', 'CT::T_ARRAY_TYPEHINT'];
        self::assertSame($expected, $tokens[4]->getNames());
        self::assertSame('array', $tokens[4]->getContent());

        $expected = [null, 'CT::T_DESTRUCTURING_SQUARE_BRACE_OPEN'];
        self::assertSame($expected, $tokens[14]->getNames());
        self::assertSame('[', $tokens[14]->getContent());
    }
}
