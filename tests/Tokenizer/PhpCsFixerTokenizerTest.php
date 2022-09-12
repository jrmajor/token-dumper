<?php

declare(strict_types=1);

namespace Tests\Tokenizer;

use Major\TokenDumper\Tokenizer as T;
use PHPUnit\Framework\TestCase;

final class PhpCsFixerTokenizerTest extends TestCase
{
    private T\Tokenizer $tokenizer;

    protected function setUp(): void
    {
        $this->tokenizer = new T\PhpCsFixerTokenizer();
    }

    public function testSimple(): void
    {
        $tokens = $this->tokenizer->tokenize("<?php echo 'Hello!';\n");

        self::assertCount(6, $tokens);

        self::assertSame('T_OPEN_TAG', $tokens[0]->getName());
        self::assertSame('<?php ', $tokens[0]->getContent());

        self::assertNull($tokens[4]->getName());
        self::assertSame(';', $tokens[4]->getContent());
    }

    public function testTransformed(): void
    {
        $tokens = $this->tokenizer->tokenize("<?php [\$a, \$b] = [1, 2];\n");

        self::assertCount(18, $tokens);

        self::assertSame('CT::T_DESTRUCTURING_SQUARE_BRACE_OPEN', $tokens[1]->getName());
        self::assertSame('[', $tokens[1]->getContent());
    }
}
