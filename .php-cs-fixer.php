<?php

$finder = PhpCsFixer\Finder::create()
    ->in(__DIR__)
    ->append(['dump-tokens'])
    ->ignoreVCSIgnored(true);

return Major\CS\config($finder)
    ->setCacheFile('.cache/.php-cs-fixer.cache');
