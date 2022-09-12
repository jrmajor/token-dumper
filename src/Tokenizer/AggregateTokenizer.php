<?php

declare(strict_types=1);

namespace Major\TokenDumper\Tokenizer;

use Psl\Iter;
use Psl\Vec;

final class AggregateTokenizer
{
    /** @var non-empty-list<Tokenizer> */
    private array $tokenizers;

    /**
     * @param non-empty-list<Tokenizer> $tokenizers
     */
    public function __construct(array $tokenizers)
    {
        $this->tokenizers = $tokenizers;
    }

    /**
     * @return list<AggregateToken>
     */
    public function tokenize(string $code): array
    {
        $listOfTokens = array_map(
            fn (Tokenizer $t) => $t->tokenize($code),
            $this->tokenizers,
        );

        return Vec\map(
            Vec\range(0, Iter\count($listOfTokens[0]) - 1),
            fn (int $i) => new AggregateToken(
                array_map(fn (array $t) => $t[$i], $listOfTokens),
            ),
        );
    }
}
