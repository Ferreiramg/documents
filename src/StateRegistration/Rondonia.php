<?php

namespace Brazanation\Documents\StateRegistration;

use Brazanation\Documents\DigitCalculator;

class Rondonia extends State
{
    const LONG_NAME = 'Rondonia';

    const REGEX = '/^(\d{13})(\d{1})$/';

    const FORMAT = '$1-$2';

    const LENGTH = 14;

    const DIGITS_COUNT = 1;

    const SHORT_NAME = 'RO';

    public function __construct()
    {
        parent::__construct(self::LONG_NAME, self::LENGTH, self::DIGITS_COUNT, self::REGEX, self::FORMAT);
    }

    /**
     * {@inheritdoc}
     *
     * @see http://www.sintegra.gov.br/Cad_Estados/cad_RO.html
     */
    public function calculateDigit($baseNumber):string
    {
        $calculator = new DigitCalculator($baseNumber);
        $calculator->useComplementaryInsteadOfModule();
        $calculator->replaceWhen('0', 10);
        $calculator->replaceWhen('1', 11);
        $calculator->withModule(DigitCalculator::MODULE_11);

        $digit = $calculator->calculate();

        return "{$digit}";
    }
}
