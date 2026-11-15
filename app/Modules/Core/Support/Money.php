<?php

namespace App\Modules\Core\Support;

use InvalidArgumentException;

/**
 * Money is stored as decimal(18,4). Arithmetic stays in strings so a
 * yarn rate of 2.3456 USD/kg does not pick up binary float error.
 */
final class Money
{
    public function __construct(
        public readonly string $amount,
        public readonly string $currency = 'BDT',
    ) {}

    public static function of(string|int|float $amount, string $currency = 'BDT'): self
    {
        return new self(self::normalize($amount), strtoupper($currency));
    }

    public static function zero(string $currency = 'BDT'): self
    {
        return self::of('0', $currency);
    }

    public function convert(string $toCurrency, string $rate): self
    {
        $to = strtoupper($toCurrency);

        if ($to === $this->currency) {
            return $this;
        }

        if (bccomp($rate, '0', 8) !== 1) {
            throw new InvalidArgumentException('Exchange rate must be greater than zero.');
        }

        return new self(self::normalize(bcmul($this->amount, $rate, 8)), $to);
    }

    public function add(self $other): self
    {
        $this->assertSameCurrency($other);

        return new self(self::normalize(bcadd($this->amount, $other->amount, 8)), $this->currency);
    }

    private function assertSameCurrency(self $other): void
    {
        if ($other->currency !== $this->currency) {
            throw new InvalidArgumentException('Cannot combine '.$this->currency.' with '.$other->currency.'.');
        }
    }

    private static function normalize(string|int|float $amount): string
    {
        $value = is_string($amount) ? trim($amount) : (string) $amount;

        if ($value === '' || ! is_numeric($value)) {
            throw new InvalidArgumentException('Money amount must be numeric.');
        }

        return bcadd($value, '0', 4);
    }
}
