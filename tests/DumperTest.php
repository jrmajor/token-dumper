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
}
