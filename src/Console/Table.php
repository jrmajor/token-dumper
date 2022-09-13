<?php

declare(strict_types=1);

namespace Major\TokenDumper\Console;

use Symfony\Component\Console\Helper\Table as BaseTable;
use Symfony\Component\Console\Helper\TableStyle;
use Symfony\Component\Console\Output\OutputInterface;

final class Table extends BaseTable
{
    public function __construct(OutputInterface $output)
    {
        $output = new OutputTrimmingProxy($output);

        parent::__construct($output);

        $style = (new TableStyle())
            ->setHorizontalBorderChars('')
            ->setVerticalBorderChars('', '<gray>|</>')
            ->setDefaultCrossingChar('');

        $this->setStyle($style);
    }
}
