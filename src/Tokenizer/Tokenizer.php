<?php

declare(strict_types=1);

namespace Major\TokenDumper\Tokenizer;

interface Tokenizer
{
    /**
     * @return list<Token>
     */
    public function tokenize(string $code): array;
}
