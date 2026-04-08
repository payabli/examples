<?php

namespace Payabli\Types;

use Payabli\Core\Json\JsonSerializableType;
use Payabli\Core\Json\JsonProperty;
use Payabli\Core\Types\ArrayType;

/**
 * Object containing details about the refund, including line items and optional split instructions.
 */
class RefundDetail extends JsonSerializableType
{
    /**
     * @var ?array<PaymentCategories> $categories Array of payment categories/line items describing the amount to be paid. Note: These categories are for information only and aren't validated against the total amount provided.
     */
    #[JsonProperty('categories'), ArrayType([PaymentCategories::class])]
    public ?array $categories;

    /**
     * @var ?array<SplitFundingRefundContent> $splitRefunding Array of objects containing split instructions for the refund.
     */
    #[JsonProperty('splitRefunding'), ArrayType([SplitFundingRefundContent::class])]
    public ?array $splitRefunding;

    /**
     * @param array{
     *   categories?: ?array<PaymentCategories>,
     *   splitRefunding?: ?array<SplitFundingRefundContent>,
     * } $values
     */
    public function __construct(
        array $values = [],
    ) {
        $this->categories = $values['categories'] ?? null;
        $this->splitRefunding = $values['splitRefunding'] ?? null;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->toJson();
    }
}
