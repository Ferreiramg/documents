<?php

namespace Brazanation\Documents\StateRegistration;

use Brazanation\Documents\DigitCalculator;

class MinasGerais extends State
{
    const LONG_NAME = 'MinasGerais';

    const REGEX = '/^(\d{3})(\d{3})(\d{3})(\d{4})$/';

    const FORMAT = '$1.$2.$3/$4';

    const LENGTH = 13;

    const DIGITS_COUNT = 1;

    const SHORT_NAME = 'MG';

    public function __construct()
    {
        parent::__construct(self::LONG_NAME, self::LENGTH, self::DIGITS_COUNT, self::REGEX, self::FORMAT);
    }

    /**
     * {@inheritdoc}
     *
     * @see http://www.sintegra.gov.br/Cad_Estados/cad_MG.html
     */
    public function calculateDigit($baseNumber):string
    {
        $calculator = new DigitCalculator($baseNumber);
        $calculator->useComplementaryInsteadOfModule();
        $calculator->withMultipliersInterval(2, 11);
        $calculator->replaceWhen('0', 10, 11);
        $calculator->withModule(DigitCalculator::MODULE_11);
        $firstDigit = $calculator->calculate();

        return "{$firstDigit}";
    }
}
