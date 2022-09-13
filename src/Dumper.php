<?php

declare(strict_types=1);

namespace Major\TokenDumper;

use Major\TokenDumper\Tokenizer as T;
use Psl\Str;
use Symfony\Component\Console\Formatter\OutputFormatter;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Helper\TableStyle;
use Symfony\Component\Console\Output\OutputInterface;

final class Dumper
{
    private T\AggregateTokenizer $tokenizer;

    private OutputInterface $out;

    public function __construct(OutputInterface $out)
    {
        $this->tokenizer = new T\AggregateTokenizer([
            new T\NativeTokenizer(),
            new T\PhpCsFixerTokenizer(),
        ]);

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

    private function nameCell(T\AggregateToken $token): string
    {
        [$native, $fixer] = $token->getNames();

        $name = [];

        if ($native !== null || $native !== $fixer) {
            $name[] = $this->formatName($native ?? 'null');
        }

        if ($native !== $fixer) {
            $name[] = '  ' . $this->formatName($fixer ?? 'null');
        }

        return Str\join($name, "\n");
    }

    private function formatName(string $name): string
    {
        if (! Str\contains($name, '::')) {
            return "<token>{$name}</>";
        }

        [$ns, $name] = Str\split($name, '::', 2);

        return "<token>{$ns}</>::<token>{$name}</>";
    }

    private function contentCell(T\AggregateToken $token): string
    {
        $content = OutputFormatter::escape($token->getContent());

        return Str\replace_every($content, [
            "\t" => '<ws>⇥</ws>',
            "\n" => '<ws>␊</ws>',
            "\r" => '<ws>␍</ws>',
            ' ' => '<ws>␣</ws>',
        ]);
    }

    private function setStyle(Table $table): void
    {
        $style = (new TableStyle())
            ->setHorizontalBorderChars('')
            ->setDefaultCrossingChar('');

        $table->setStyle($style);
    }
}
