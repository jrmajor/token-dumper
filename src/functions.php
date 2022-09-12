<?php

declare(strict_types=1);

use Major\TokenDumper\Dumper;
use Symfony\Component\Console\Output\ConsoleOutput;

function dump_tokens(string $source): void
{
    $dumper = new Dumper(new ConsoleOutput());

    $dumper->dump($source);
}
