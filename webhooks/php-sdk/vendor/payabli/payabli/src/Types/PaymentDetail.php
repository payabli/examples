<?php

namespace Payabli\Types;

use Payabli\Core\Json\JsonSerializableType;
use Payabli\Core\Json\JsonProperty;
use Payabli\Core\Types\ArrayType;

/**
 * Details about the payment.
 */
class PaymentDetail extends JsonSerializableType
{
    /**
     * Array of payment categories/line items describing the amount to be paid.
     * **Note**: These categories are for information only and aren't validated against the total amount provided.
     *
     * @var ?array<PaymentCategories> $categories
     */
    #[JsonProperty('categories'), ArrayType([PaymentCategories::class])]
    public ?array $categories;

    /**
     * @var ?array<string, mixed> $checkImage Object containing image of paper check.
     */
    #[JsonProperty('checkImage'), ArrayType(['string' => 'mixed'])]
    public ?array $checkImage;

    /**
     * @var ?string $checkNumber A check number to be used in the ach transaction. **Required** for payment method = 'check'.
     */
    #[JsonProperty('checkNumber')]
    public ?string $checkNumber;

    /**
     * @var ?string $currency The currency for the transaction, `USD` or `CAD`. If your paypoint is configured for CAD, you must send the `CAD` value in this field, otherwise it defaults to USD, which will cause the transaction to fail.
     */
    #[JsonProperty('currency')]
    public ?string $currency;

    /**
     * @var ?float $serviceFee Service fee to be deducted from the total amount. This amount must be a number, percentages aren't accepted. If you are using a percentage-based fee schedule, you must calculate the value manually.
     */
    #[JsonProperty('serviceFee')]
    public ?float $serviceFee;

    /**
     * @var ?array<SplitFundingContent> $splitFunding Split funding instructions for the transaction. See [Split a Transaction](/developers/developer-guides/money-in-split-funding) for more.
     */
    #[JsonProperty('splitFunding'), ArrayType([SplitFundingContent::class])]
    public ?array $splitFunding;

    /**
     * @var float $totalAmount Total amount to be charged. If a service fee is sent, then this amount should include the service fee."
     */
    #[JsonProperty('totalAmount')]
    public float $totalAmount;

    /**
     * @param array{
     *   totalAmount: float,
     *   categories?: ?array<PaymentCategories>,
     *   checkImage?: ?array<string, mixed>,
     *   checkNumber?: ?string,
     *   currency?: ?string,
     *   serviceFee?: ?float,
     *   splitFunding?: ?array<SplitFundingContent>,
     * } $values
     */
    public function __construct(
        array $values,
    ) {
        $this->categories = $values['categories'] ?? null;
        $this->checkImage = $values['checkImage'] ?? null;
        $this->checkNumber = $values['checkNumber'] ?? null;
        $this->currency = $values['currency'] ?? null;
        $this->serviceFee = $values['serviceFee'] ?? null;
        $this->splitFunding = $values['splitFunding'] ?? null;
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
