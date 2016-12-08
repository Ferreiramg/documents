<?php

namespace Brazanation\Documents\Tests;

use Brazanation\Documents\Voter;

class VoterTest extends DocumentTestCase
{
    public function createDocument($number)
    {
        return new Voter($number);
    }

    public function provideValidNumbers()
    {
        return [
            'GO' => ['247003181023'],
            'MA' => ['117667321139'],
            'MT' => ['502586121856'],
            'MS' => ['684744341910'],
            'MG' => ['122507810299'],
            'SP' => ['106644440302'],
        ];
    }

    public function provideValidNumbersAndExpectedFormat()
    {
        return [
            ['106644440302', '106644440302'],
        ];
    }

    public function provideEmptyData()
    {
        return [
            [Voter::LABEL, '']
        ];
    }

    public function provideInvalidNumber()
    {
        return [
            [Voter::LABEL, '1'],
            [Voter::LABEL, '123123232323'],
            [Voter::LABEL, '861238979874'],
        ];
    }
}
