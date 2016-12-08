<?php
declare(strict_types=1);
namespace Brazanation\Documents;

use Brazanation\Documents\Cns\CnsCalculator;
use Brazanation\Documents\Cns\TemporaryCalculator;

final class Cns extends AbstractDocument
{
    const LENGTH = 15;
    const LABEL = 'CNS';
    const REGEX = '/^([\d]{3})([\d]{4})([\d]{4})([\d]{4})$/';
    const FORMAT = '$1 $2 $3 $4';
    const DIGIT_COUNT = 1;

    /**
     * CNS constructor.
     *
     * @param string $number
     */
    public function __construct($number)
    {
        $number = preg_replace('/\D/', '', $number);
        parent::__construct($number, self::LENGTH, self::DIGIT_COUNT, self::LABEL);
    }

    /**
     * {@inheritdoc}
     */
    public function format(): string
    {
        return preg_replace(self::REGEX, self::FORMAT, "{$this}");
    }

    /**
     * {@inheritdoc}
     *
     * Based on given number, it will decide what kind of calculator will use.
     *
     * For numbers starting with 7, 8 or 9 will use TemporaryCalculator,
     * otherwise CnsCalculator.
     */
    public function calculateDigit($baseNumber): string
    {
        $calculator = new CnsCalculator();

        if (in_array(substr($baseNumber, 0, 1), [7, 8, 9])) {
            $calculator = new TemporaryCalculator();
        }

        $digit = $calculator->calculateDigit($baseNumber);

        return "{$digit}";
    }
}
