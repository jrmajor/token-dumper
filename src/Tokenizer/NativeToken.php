<?php

declare(strict_types=1);

namespace Major\TokenDumper\Tokenizer;

final class NativeToken implements Token
{
    /** @var string|array{int, string, int} */
    private $token;

    /**
     * @param string|array{int, string, int} $token
     */
    public function __construct($token)
    {
        $this->token = $token;
    }

    public function getName(): ?string
    {
        return is_array($this->token) ? token_name($this->token[0]) : null;
    }

    public function getContent(): string
    {
        return is_string($this->token) ? $this->token : $this->token[1];
    }
}
