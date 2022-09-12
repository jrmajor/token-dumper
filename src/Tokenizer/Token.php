<?php

declare(strict_types=1);

namespace Major\TokenDumper\Tokenizer;

interface Token
{
    public function getName(): ?string;

    public function getContent(): string;
}
