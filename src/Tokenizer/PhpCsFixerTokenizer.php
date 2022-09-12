<?php

declare(strict_types=1);

namespace Major\TokenDumper\Tokenizer;

use PhpCsFixer\Tokenizer\Token as BaseToken;
use PhpCsFixer\Tokenizer\Tokens as BaseTokens;
use Psl\Vec;

final class PhpCsFixerTokenizer implements Tokenizer
{
    /**
     * @return list<PhpCsFixerToken>
     */
    public function tokenize(string $code): array
    {
        return Vec\map(
            BaseTokens::fromCode($code),
            fn (BaseToken $t) => new PhpCsFixerToken($t),
        );
    }
}
