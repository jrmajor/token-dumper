<?php

declare(strict_types=1);

use Major\TokenDumper\Console\OutputFormatter;
use Major\TokenDumper\Dumper;
use Symfony\Component\Console\Output\ConsoleOutput;
use Symfony\Component\Console\Output\OutputInterface;

function dump_tokens(string $source): void
{
    $out = new ConsoleOutput(
        OutputInterface::VERBOSITY_NORMAL,
        null,
        new OutputFormatter(),
    );

    $dumper = new Dumper($out);

    $dumper->dump($source);
}
