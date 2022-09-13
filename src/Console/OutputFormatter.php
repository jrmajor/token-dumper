<?php

declare(strict_types=1);

namespace Major\TokenDumper\Console;

use Symfony\Component\Console\Formatter\OutputFormatter as BaseFormatter;
use Symfony\Component\Console\Formatter\OutputFormatterStyle as Style;

final class OutputFormatter extends BaseFormatter
{
    public function __construct()
    {
        parent::__construct(false, [
            'ws' => new Style('yellow'),
        ]);
    }
}
