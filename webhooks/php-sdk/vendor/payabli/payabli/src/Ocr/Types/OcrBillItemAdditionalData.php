<?php

namespace Payabli\Ocr\Types;

use Payabli\Core\Json\JsonSerializableType;
use Payabli\Core\Json\JsonProperty;

class OcrBillItemAdditionalData extends JsonSerializableType
{
    /**
     * @var ?string $category
     */
    #[JsonProperty('category')]
    public ?string $category;

    /**
     * @var ?string $currencyCode
     */
    #[JsonProperty('currency_code')]
    public ?string $currencyCode;

    /**
     * @var ?string $type
     */
    #[JsonProperty('type')]
    public ?string $type;

    /**
     * @var ?string $referenceNumber
     */
    #[JsonProperty('reference_number')]
    public ?string $referenceNumber;

    /**
     * @param array{
     *   category?: ?string,
     *   currencyCode?: ?string,
     *   type?: ?string,
     *   referenceNumber?: ?string,
     * } $values
     */
    public function __construct(
        array $values = [],
    ) {
        $this->category = $values['category'] ?? null;
        $this->currencyCode = $values['currencyCode'] ?? null;
        $this->type = $values['type'] ?? null;
        $this->referenceNumber = $values['referenceNumber'] ?? null;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->toJson();
    }
}
