<?php

namespace Payabli\MoneyIn\Types;

use Payabli\Core\Json\JsonSerializableType;
use Payabli\Core\Json\JsonProperty;

class CapturePaymentDetails extends JsonSerializableType
{
    /**
     * Total amount to be captured, including the `serviceFee` amount. The amount can't be greater the original
     * total amount of the transaction, and can't be more than 15% lower than the original amount.
     *
     * @var float $totalAmount
     */
    #[JsonProperty('totalAmount')]
    public float $totalAmount;

    /**
     * @var ?float $serviceFee Service fee to capture for the transaction.
     */
    #[JsonProperty('serviceFee')]
    public ?float $serviceFee;

    /**
     * @param array{
     *   totalAmount: float,
     *   serviceFee?: ?float,
     * } $values
     */
    public function __construct(
        array $values,
    ) {
        $this->totalAmount = $values['totalAmount'];
        $this->serviceFee = $values['serviceFee'] ?? null;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->toJson();
    }
}
