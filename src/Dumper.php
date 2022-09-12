<?php

declare(strict_types=1);

namespace Major\TokenDumper;

use Major\TokenDumper\Tokenizer as T;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Helper\TableStyle;
use Symfony\Component\Console\Output\OutputInterface;

final class Dumper
{
    private T\Tokenizer $tokenizer;

    private OutputInterface $out;

    public function __construct(OutputInterface $out)
    {
        $this->tokenizer = new T\NativeTokenizer();

        $this->out = $out;
    }

    public function dump(string $source): void
    {
        $tokens = $this->tokenizer->tokenize($source);

        $table = new Table($this->out);
        $this->setStyle($table);

        foreach ($tokens as $i => $token) {
            $table->addRow([
                $i,
                $this->nameCell($token),
                $this->contentCell($token),
            ]);
        }

        $table->render();
    }

    private function nameCell(T\Token $token): string
    {
        return $token->getName() ?? '';
    }

    private function contentCell(T\Token $token): string
    {
        return $token->getContent();
    }

    private function setStyle(Table $table): void
    {
        $style = (new TableStyle())
            ->setHorizontalBorderChars('')
            ->setDefaultCrossingChar('');

        $table->setStyle($style);
    }
}
