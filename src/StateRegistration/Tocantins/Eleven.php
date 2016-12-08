<?php

namespace Brazanation\Documents\StateRegistration\Tocantins;

use Brazanation\Documents\DigitCalculator;
use Brazanation\Documents\StateRegistration\State;
use Brazanation\Documents\StateRegistration\Tocantins;

class Eleven extends State
{
    const REGEX = '/^(\d{2})(\d{2})(\d{3})(\d{3})(\d{1})$/';

    const FORMAT = '$1.$2.$3.$4-$5';

    const LENGTH = 11;

    const DIGITS_COUNT = 1;

    public function __construct()
    {
        parent::__construct(Tocantins::LONG_NAME, self::LENGTH, self::DIGITS_COUNT, self::REGEX, self::FORMAT);
    }

    /**
     * {@inheritdoc}
     */
    public function extractBaseNumber($number):string
    {
        $baseNumber = parent::extractBaseNumber($number);

        return $this->removeFixedDigits($baseNumber);
    }

    /**
     * {@inheritdoc}
     *
     * @see http://www.sintegra.gov.br/Cad_Estados/cad_TO.html
     */
    public function calculateDigit($baseNumber):string
    {
        $calculator = new DigitCalculator($baseNumber);
        $calculator->useComplementaryInsteadOfModule();
        $calculator->replaceWhen('0', 10, 11);

        $digit = $calculator->calculate();

        return "{$digit}";
    }

    /**
     * Removes digits should not goes to calculate digit.
     *
     * They digits are in position 3 and 4.
     *
     * @param string $baseNumber Base number.
     *
     * @return string Returns base number without fixed digits.
     */
    private function removeFixedDigits($baseNumber)
    {
        return substr($baseNumber, 0, 2) . substr($baseNumber, 4);
    }
}
