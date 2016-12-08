<?php
declare(strict_types=1);
namespace Brazanation\Documents;

final class NFeAccessKey extends AbstractDocument
{

    const LABEL = 'NFeAccessKey';
    const LENGTH = 44;
    const REGEX = '/([\d]{4})/';
    const MASK = '$1 ';
    const MODEL = 55;

    /**
     * NFeAccessKey constructor.
     *
     * @param $nfeKey
     */
    public function __construct(string $nfeKey)
    {
        $nfeKey = preg_replace('/\D/', '', $nfeKey);
        parent::__construct($nfeKey, self::LENGTH, 1, self::LABEL);
    }

    /**
     * Generates a valid NFe Access Key.
     *
     * @param int       $state         IBGE state code.
     * @param \DateTime $generatedAt   Year and month when invoice was created.
     * @param Cnpj      $cnpj          Cnpj from issuer.
     * @param int       $sequence      Invoice sequence.
     * @param int       $invoiceNumber Invoice number.
     * @param string       $controlNumber Control number.
     *
     * @return NFeAccessKey
     */
    public static function generate(
    $state, \DateTime $generatedAt, Cnpj $cnpj, int $sequence, int $invoiceNumber, string $controlNumber
    )
    {
        $yearMonth = $generatedAt->format('ym');
        $sequence = sprintf('%03d',$sequence);//str_pad($sequence, 3, 0, STR_PAD_LEFT);
        $model = self::MODEL;
        $invoiceNumber = sprintf('%09d',$invoiceNumber);//str_pad($invoiceNumber, 9, 0, STR_PAD_LEFT);
        $controlNumber = sprintf('%09d',$controlNumber);//str_pad($controlNumber, 9, 0, STR_PAD_LEFT);

        $baseNumber = "{$state}{$yearMonth}{$cnpj}{$model}{$sequence}{$invoiceNumber}{$controlNumber}";

        $digit = self::calculateDigitFrom($baseNumber);

        return new self("{$baseNumber}{$digit}");
    }

    /**
     * {@inheritdoc}
     */
    public function format(): string
    {
        return trim(preg_replace(self::REGEX, self::MASK, "{$this}"));
    }

    /**
     * {@inheritdoc}
     */
    public function calculateDigit($baseNumber): string
    {
        return self::calculateDigitFrom($baseNumber);
    }

    /**
     * Calculate check digit from base number.
     *
     * It is static because is used from generate static method.
     *
     * @param string $baseNumber Base numeric section to be calculate your digit.
     *
     * @return string
     */
    private static function calculateDigitFrom($baseNumber): string
    {
        $calculator = new DigitCalculator($baseNumber);
        $calculator->useComplementaryInsteadOfModule();
        $calculator->withModule(DigitCalculator::MODULE_11);
        $digit = $calculator->calculate();

        return "{$digit}";
    }
}
