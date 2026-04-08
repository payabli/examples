<?php

namespace Payabli\Types;

use Payabli\Core\Json\JsonSerializableType;
use Payabli\Core\Json\JsonProperty;

/**
 * The PaymentDetail object for microdeposit (MakeCredit) transactions.
 */
class PaymentDetailCredit extends JsonSerializableType
{
    /**
     * @var ?string $currency Currency code ISO-4217. If not code is provided the currency in the paypoint setting is taken. Default is **USD**
     */
    #[JsonProperty('currency')]
    public ?string $currency;

    /**
     * @var ?float $serviceFee Service fee to be deducted from the total amount. This amount must be a number, percentages aren't accepted. If you are using a percentage-based fee schedule, you must calculate the value manually.
     */
    #[JsonProperty('serviceFee')]
    public ?float $serviceFee;

    /**
     * @var float $totalAmount Total amount to be charged. If a service fee is provided, then this amount should include the service fee.
     */
    #[JsonProperty('totalAmount')]
    public float $totalAmount;

    /**
     * @param array{
     *   totalAmount: float,
     *   currency?: ?string,
     *   serviceFee?: ?float,
     * } $values
     */
    public function __construct(
        array $values,
    ) {
        $this->currency = $values['currency'] ?? null;
        $this->serviceFee = $values['serviceFee'] ?? null;
        $this->totalAmount = $values['totalAmount'];
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->toJson();
    }
}
