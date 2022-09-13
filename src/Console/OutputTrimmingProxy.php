<?php

declare(strict_types=1);

namespace Major\TokenDumper\Console;

use Psl\Regex;
use Psl\Vec;
use Symfony\Component\Console\Formatter\OutputFormatterInterface;
use Symfony\Component\Console\Output\OutputInterface;

final class OutputTrimmingProxy implements OutputInterface
{
    private OutputInterface $output;

    public function __construct(OutputInterface $output)
    {
        $this->output = $output;
    }

    /**
     * @param string|iterable<string> $messages
     *
     * @psalm-suppress MoreSpecificImplementedParamType
     */
    public function write($messages, bool $newline = false, int $options = 0): void
    {
        $this->output->write($this->trimIterable($messages), $newline, $options);
    }

    /**
     * @param string|iterable<string> $messages
     *
     * @psalm-suppress MoreSpecificImplementedParamType
     */
    public function writeln($messages, int $options = 0): void
    {
        $this->output->writeln($this->trimIterable($messages), $options);
    }

    private function trim(string $string): string
    {
        return Regex\replace_with($string, '/ +(\\n|$)/', fn ($m) => $m[1]);
    }

    /**
     * @param string|iterable<string> $strings
     * @return list<string>
     */
    private function trimIterable($strings): array
    {
        $strings = is_string($strings) ? [$strings] : $strings;

        return Vec\map($strings, fn (string $m) => $this->trim($m));
    }

    public function setVerbosity(int $level): void
    {
        $this->output->setVerbosity($level);
    }

    public function getVerbosity(): int
    {
        return $this->output->getVerbosity();
    }

    public function isQuiet(): bool
    {
        return $this->output->isQuiet();
    }

    public function isVerbose(): bool
    {
        return $this->output->isVerbose();
    }

    public function isVeryVerbose(): bool
    {
        return $this->output->isVeryVerbose();
    }

    public function isDebug(): bool
    {
        return $this->output->isDebug();
    }

    public function setDecorated(bool $decorated): void
    {
        $this->output->setDecorated($decorated);
    }

    public function isDecorated(): bool
    {
        return $this->output->isDecorated();
    }

    public function setFormatter(OutputFormatterInterface $formatter): void
    {
        $this->output->setFormatter($formatter);
    }

    public function getFormatter(): OutputFormatterInterface
    {
        return $this->output->getFormatter();
    }
}
