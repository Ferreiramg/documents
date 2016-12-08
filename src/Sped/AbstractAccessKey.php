<?php
declare(strict_types = 1);
namespace Brazanation\Documents\Sped;

use Brazanation\Documents\AbstractDocument;
use Brazanation\Documents\Cnpj;
use Brazanation\Documents\DigitCalculator;

/**
 * Class SpedAccessKey
 *
 * @package Brazanation\Documents
 *
 * @property int       $state
 * @property \DateTime $generatedAt
 * @property Cnpj      $cnpj
 * @property int       $model
 * @property int       $sequence
 * @property int       $invoiceNumber
 * @property int       $controlNumber
 */
abstract class AbstractAccessKey extends AbstractDocument
{
    const LABEL = 'SpedAccessKey';

    const LENGTH = 44;

    const REGEX = '/([\d]{4})/';

    const MASK = '$1 ';

    protected $state;

    protected $generatedAt;

    protected $cnpj;

    protected $model;

    protected $sequence;

    protected $invoiceNumber;

    protected $controlNumber;

    /**
     * SpedAccessKey constructor.
     *
     * @param $accessKey
     */
    public function __construct($accessKey)
    {
        $accessKey = preg_replace('/\D/', '', $accessKey);
        parent::__construct($accessKey, static::LENGTH, 1, static::LABEL);
        $this->loadFromKey($accessKey);
    }

    private function loadFromKey($accessKey)
    {
        $startPosition = 0;
        $this->state = substr($accessKey, $startPosition, 2);

        $startPosition += 2;
        $this->generatedAt = \DateTime::createFromFormat('ymd H:i:s', substr($accessKey, $startPosition, 4) . '01 00:00:00');

        $startPosition += 4;
        $this->cnpj = new Cnpj(substr($accessKey, $startPosition, 14));

        $startPosition += 14;
        $this->model = new Model(substr($accessKey, $startPosition, 2));

        $startPosition += 2;
        $this->sequence = substr($accessKey, $startPosition, 3);

        $startPosition += 3;
        $this->invoiceNumber = substr($accessKey, $startPosition, 9);

        $startPosition += 9;
        $this->controlNumber = substr($accessKey, $startPosition, 9);

        $startPosition += 9;
        $this->digit = substr($accessKey, $startPosition, 1);
    }

    /**
     * Generates a valid Sped Access Key.
     *
     * @param int       $state         IBGE state code.
     * @param \DateTime $generatedAt   Year and month when invoice was created.
     * @param Cnpj      $cnpj          Cnpj from issuer.
     * @param Model     $model         Document model.
     * @param int       $sequence      Invoice sequence.
     * @param int       $invoiceNumber Invoice number.
     * @param int       $controlNumber Control number.
     *
     * @return AbstractAccessKey
     */
    protected static function generateKey(
        $state,
        \DateTime $generatedAt,
        Cnpj $cnpj,
        Model $model,
        int $sequence,
        int $invoiceNumber,
        int $controlNumber
    ) {
        $yearMonth = $generatedAt->format('ym');
        $sequence = sprintf("%03d",$sequence);
        $invoiceNumber = sprintf("%09d",$invoiceNumber);
        $controlNumber = sprintf("%09d",$controlNumber);

        $baseNumber = "{$state}{$yearMonth}{$cnpj}{$model}{$sequence}{$invoiceNumber}{$controlNumber}";

        $digit = self::calculateDigitFrom($baseNumber);

        $instance = new static("{$baseNumber}{$digit}");
        $instance->generatedAt = $generatedAt;

        return $instance;
    }

    /**
     * {@inheritdoc}
     */
    public function format():string
    {
        return trim(preg_replace(self::REGEX, self::MASK, "{$this}"));
    }

    /**
     * {@inheritdoc}
     */
    public function calculateDigit($baseNumber):string
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
    public static function calculateDigitFrom($baseNumber):string
    {
        $calculator = new DigitCalculator($baseNumber);
        $calculator->useComplementaryInsteadOfModule();
        $calculator->withModule(DigitCalculator::MODULE_11);
        $digit = $calculator->calculate();

        return "{$digit}";
    }
}
