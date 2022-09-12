<?php

declare(strict_types=1);

namespace Tests;

use Major\TokenDumper\Dumper;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Output\BufferedOutput;

final class DumperTest extends TestCase
{
    private BufferedOutput $out;

    private Dumper $dumper;

    public function setUp(): void
    {
        $this->out = new BufferedOutput();
        $this->dumper = new Dumper($this->out);
    }

    public function testSimpleDump(): void
    {
        $this->dumper->dump('<?php echo 1;');

        $expected = <<<'OUT'
            | 0 | T_OPEN_TAG   | <?php  |
            | 1 | T_ECHO       | echo   |
            | 2 | T_WHITESPACE |        |
            | 3 | T_LNUMBER    | 1      |
            | 4 |              | ;      |

            OUT;

        self::assertSame($expected, $this->out->fetch());
    }
}
