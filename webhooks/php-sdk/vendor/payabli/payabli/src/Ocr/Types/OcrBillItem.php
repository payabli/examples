<?php

namespace Payabli\Ocr\Types;

use Payabli\Core\Json\JsonSerializableType;
use Payabli\Core\Json\JsonProperty;
use Payabli\Core\Types\ArrayType;

class OcrBillItem extends JsonSerializableType
{
    /**
     * @var ?float $itemTotalAmount
     */
    #[JsonProperty('itemTotalAmount')]
    public ?float $itemTotalAmount;

    /**
     * @var ?float $itemTaxAmount
     */
    #[JsonProperty('itemTaxAmount')]
    public ?float $itemTaxAmount;

    /**
     * @var ?float $itemTaxRate
     */
    #[JsonProperty('itemTaxRate')]
    public ?float $itemTaxRate;

    /**
     * @var ?string $itemProductCode
     */
    #[JsonProperty('itemProductCode')]
    public ?string $itemProductCode;

    /**
     * @var ?string $itemProductName
     */
    #[JsonProperty('itemProductName')]
    public ?string $itemProductName;

    /**
     * @var ?string $itemDescription
     */
    #[JsonProperty('itemDescription')]
    public ?string $itemDescription;

    /**
     * @var ?string $itemCommodityCode
     */
    #[JsonProperty('itemCommodityCode')]
    public ?string $itemCommodityCode;

    /**
     * @var ?string $itemUnitOfMeasure
     */
    #[JsonProperty('itemUnitOfMeasure')]
    public ?string $itemUnitOfMeasure;

    /**
     * @var ?float $itemCost
     */
    #[JsonProperty('itemCost')]
    public ?float $itemCost;

    /**
     * @var ?int $itemQty
     */
    #[JsonProperty('itemQty')]
    public ?int $itemQty;

    /**
     * @var ?int $itemMode
     */
    #[JsonProperty('itemMode')]
    public ?int $itemMode;

    /**
     * @var ?array<string> $itemCategories
     */
    #[JsonProperty('itemCategories'), ArrayType(['string'])]
    public ?array $itemCategories;

    /**
     * @param array{
     *   itemTotalAmount?: ?float,
     *   itemTaxAmount?: ?float,
     *   itemTaxRate?: ?float,
     *   itemProductCode?: ?string,
     *   itemProductName?: ?string,
     *   itemDescription?: ?string,
     *   itemCommodityCode?: ?string,
     *   itemUnitOfMeasure?: ?string,
     *   itemCost?: ?float,
     *   itemQty?: ?int,
     *   itemMode?: ?int,
     *   itemCategories?: ?array<string>,
     * } $values
     */
    public function __construct(
        array $values = [],
    ) {
        $this->itemTotalAmount = $values['itemTotalAmount'] ?? null;
        $this->itemTaxAmount = $values['itemTaxAmount'] ?? null;
        $this->itemTaxRate = $values['itemTaxRate'] ?? null;
        $this->itemProductCode = $values['itemProductCode'] ?? null;
        $this->itemProductName = $values['itemProductName'] ?? null;
        $this->itemDescription = $values['itemDescription'] ?? null;
        $this->itemCommodityCode = $values['itemCommodityCode'] ?? null;
        $this->itemUnitOfMeasure = $values['itemUnitOfMeasure'] ?? null;
        $this->itemCost = $values['itemCost'] ?? null;
        $this->itemQty = $values['itemQty'] ?? null;
        $this->itemMode = $values['itemMode'] ?? null;
        $this->itemCategories = $values['itemCategories'] ?? null;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->toJson();
    }
}
