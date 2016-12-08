<?php

namespace Brazanation\Documents\StateRegistration;

use Brazanation\Documents\DigitCalculator;

class RioDeJaneiro extends State
{
    const LONG_NAME = 'RioDeJaneiro';

    const REGEX = '/^(\d{2})(\d{3})(\d{3})$/';

    const FORMAT = '$1.$2.$3';

    const LENGTH = 8;

    const DIGITS_COUNT = 1;

    const SHORT_NAME = 'RJ';

    public function __construct()
    {
        parent::__construct(self::LONG_NAME, self::LENGTH, self::DIGITS_COUNT, self::REGEX, self::FORMAT);
    }

    /**
     * {@inheritdoc}
     *
     * @see http://www.sintegra.gov.br/Cad_Estados/cad_RJ.html
     */
    public function calculateDigit($baseNumber):string
    {
        $calculator = new DigitCalculator($baseNumber);
        $calculator->useComplementaryInsteadOfModule();
        $calculator->withMultipliersInterval(2, 7);
        $calculator->replaceWhen('0', 10, 11);
        $calculator->withModule(DigitCalculator::MODULE_11);

        $digit = $calculator->calculate();

        return "{$digit}";
    }
}
