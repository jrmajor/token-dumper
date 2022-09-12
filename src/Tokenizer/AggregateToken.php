<?php

declare(strict_types=1);

namespace Major\TokenDumper\Tokenizer;

use Psl\Vec;

final class AggregateToken
{
    /** @var non-empty-list<Token> */
    private array $tokens;

    /**
     * @param non-empty-list<Token> $tokens
     */
    public function __construct(
        array $tokens
    ) {
        $this->tokens = $tokens;
    }

    /**
     * @return list<?string>
     */
    public function getNames(): array
    {
        return Vec\map($this->tokens, fn (Token $t) => $t->getName());
    }

    public function getContent(): string
    {
        return $this->tokens[0]->getContent();
    }
}
