<?php
declare(strict_types=1);
namespace Brazanation\Documents\Cns;

use Brazanation\Documents\DigitCalculable;
use Brazanation\Documents\DigitCalculator;

class TemporaryCalculator implements DigitCalculable
{
    /**
     * {@inheritdoc}
     */
    public function calculateDigit($baseNumber): string
    {
        $calculator = new DigitCalculator($baseNumber);
        $calculator->withMultipliersInterval(1, 15);

        $digit = $calculator->calculate();

        return "{$digit}";
    }
}
