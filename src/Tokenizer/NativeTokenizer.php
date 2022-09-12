<?php

declare(strict_types=1);

namespace Major\TokenDumper\Tokenizer;

use Psl\Vec;

final class NativeTokenizer implements Tokenizer
{
    /**
     * @return list<NativeToken>
     */
    public function tokenize(string $code): array
    {
        return Vec\map(
            token_get_all($code),
            fn ($t) => new NativeToken($t),
        );
    }
}
