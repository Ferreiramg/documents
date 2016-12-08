<?php
declare(strict_types=1);
namespace Brazanation\Documents\Cns;

use Brazanation\Documents\DigitCalculable;
use Brazanation\Documents\DigitCalculator;

final class CnsCalculator implements DigitCalculable
{
    /**
     * {@inheritdoc}
     */
    public function calculateDigit($baseNumber): string
    {
        $pis = substr($baseNumber, 0, 11);

        $calculator = new DigitCalculator($pis);
        $calculator->useComplementaryInsteadOfModule();
        $calculator->replaceWhen('8', 10);
        $calculator->replaceWhen('0', 11);
        $calculator->withMultipliersInterval(5, 15);

        $digit = $calculator->calculate();

        return "{$digit}";
    }
}
