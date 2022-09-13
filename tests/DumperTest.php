<?php

declare(strict_types=1);

namespace Tests;

use Major\TokenDumper\Console\OutputFormatter;
use Major\TokenDumper\Dumper;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Output\BufferedOutput;

final class DumperTest extends TestCase
{
    private BufferedOutput $out;

    private Dumper $dumper;

    public function setUp(): void
    {
        $this->out = new BufferedOutput(null, false, new OutputFormatter());

        $this->dumper = new Dumper($this->out);
    }

    public function testSimpleDump(): void
    {
        $this->dumper->dump('<?php echo 1;');

        $expected = <<<'OUT'
            | 0 | T_OPEN_TAG   | <?php␣ |
            | 1 | T_ECHO       | echo   |
            | 2 | T_WHITESPACE | ␣      |
            | 3 | T_LNUMBER    | 1      |
            | 4 |              | ;      |
            OUT;

        self::assertSame("{$expected}\n", $this->out->fetch());
    }

    public function testFormattingEscapes(): void
    {
        $this->dumper->dump("<info>x</>\n<foo>test</>");

        $expected = <<<'OUT'
            | 0 | T_INLINE_HTML | <info>x</>␊<foo>test</> |
            OUT;

        self::assertSame("{$expected}\n", $this->out->fetch());
    }

    public function testNonPrintableCharacters(): void
    {
        $this->dumper->dump("<?php\n\techo '\\n';\r\n");

        $expected = <<<'OUT'
            | 0 | T_OPEN_TAG                 | <?php␊ |
            | 1 | T_WHITESPACE               | ⇥      |
            | 2 | T_ECHO                     | echo   |
            | 3 | T_WHITESPACE               | ␣      |
            | 4 | T_CONSTANT_ENCAPSED_STRING | '\n'   |
            | 5 |                            | ;      |
            | 6 | T_WHITESPACE               | ␍␊     |
            OUT;

        self::assertSame("{$expected}\n", $this->out->fetch());
    }

    public function testCustomTokens(): void
    {
        $this->dumper->dump('<?php use const PI; fn (): ?array => null;');

        $expected = <<<'OUT'
            | 0  | T_OPEN_TAG             | <?php␣ |
            | 1  | T_USE                  | use    |
            | 2  | T_WHITESPACE           | ␣      |
            | 3  | T_CONST                | const  |
            |    |   CT::T_CONST_IMPORT   |        |
            | 4  | T_WHITESPACE           | ␣      |
            | 5  | T_STRING               | PI     |
            | 6  |                        | ;      |
            | 7  | T_WHITESPACE           | ␣      |
            | 8  | T_FN                   | fn     |
            | 9  | T_WHITESPACE           | ␣      |
            | 10 |                        | (      |
            | 11 |                        | )      |
            | 12 | null                   | :      |
            |    |   CT::T_TYPE_COLON     |        |
            | 13 | T_WHITESPACE           | ␣      |
            | 14 | null                   | ?      |
            |    |   CT::T_NULLABLE_TYPE  |        |
            | 15 | T_ARRAY                | array  |
            |    |   CT::T_ARRAY_TYPEHINT |        |
            | 16 | T_WHITESPACE           | ␣      |
            | 17 | T_DOUBLE_ARROW         | =>     |
            | 18 | T_WHITESPACE           | ␣      |
            | 19 | T_STRING               | null   |
            | 20 |                        | ;      |
            OUT;

        self::assertSame("{$expected}\n", $this->out->fetch());
    }
}
