<?php

namespace Payabli\MoneyOutTypes\Types;

use Payabli\Core\Json\JsonSerializableType;
use Payabli\Core\Json\JsonProperty;

/**
 * Object containing payment details.
 */
class RequestOutAuthorizePaymentDetails extends JsonSerializableType
{
    /**
     * @var ?string $checkNumber
     */
    #[JsonProperty('checkNumber')]
    public ?string $checkNumber;

    /**
     * @var ?string $currency Currency code ISO-4217. If not code is provided the currency in the paypoint setting is taken. Default is **USD**.
     */
    #[JsonProperty('currency')]
    public ?string $currency;

    /**
     * @var ?float $serviceFee Service fee to be deducted from the total amount. This amount must be a number, percentages aren't accepted. If you are using a percentage-based fee schedule, you must calculate the value manually.
     */
    #[JsonProperty('serviceFee')]
    public ?float $serviceFee;

    /**
     * @var ?float $totalAmount Total amount to be charged. If a service fee is included, then this amount should include the service fee.
     */
    #[JsonProperty('totalAmount')]
    public ?float $totalAmount;

    /**
     * @param array{
     *   checkNumber?: ?string,
     *   currency?: ?string,
     *   serviceFee?: ?float,
     *   totalAmount?: ?float,
     * } $values
     */
    public function __construct(
        array $values = [],
    ) {
        $this->checkNumber = $values['checkNumber'] ?? null;
        $this->currency = $values['currency'] ?? null;
        $this->serviceFee = $values['serviceFee'] ?? null;
        $this->totalAmount = $values['totalAmount'] ?? null;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->toJson();
    }
}
