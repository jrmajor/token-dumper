<?php

declare(strict_types=1);

namespace Major\TokenDumper\Tokenizer;

use PhpCsFixer\Tokenizer\Token as BaseToken;

final class PhpCsFixerToken implements Token
{
    private BaseToken $token;

    public function __construct(BaseToken $token)
    {
        $this->token = $token;
    }

    public function getName(): ?string
    {
        return $this->token->getName();
    }

    public function getContent(): string
    {
        return $this->token->getContent();
    }
}
