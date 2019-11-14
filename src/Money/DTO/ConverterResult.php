<?php
declare(strict_types=1);

namespace DpDocument\Facades\Money\DTO;

/**
 * Class ConverterResult
 *
 * @package DpDocument\Facades\Money
 * @since   1.1.0
 * DpDocument | Research & Development
 */
final class ConverterResult
{
    /** @var int|float */
    public $amount;
    /** @var string */
    public $currency;

    /**
     * ConverterResult constructor.
     *
     * @param int|float $amount
     * @param string    $currency
     */
    public function __construct($amount, string $currency)
    {
        $this->amount   = $amount;
        $this->currency = $currency;
    }
}
