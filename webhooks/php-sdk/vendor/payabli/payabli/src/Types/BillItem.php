<?php

namespace Payabli\Types;

use Payabli\Core\Json\JsonSerializableType;
use Payabli\Core\Json\JsonProperty;
use Payabli\Core\Types\ArrayType;
use Payabli\Core\Types\Union;

class BillItem extends JsonSerializableType
{
    /**
     * @var ?array<?string> $itemCategories Array of tags classifying item or product.
     */
    #[JsonProperty('itemCategories'), ArrayType([new Union('string', 'null')])]
    public ?array $itemCategories;

    /**
     * @var ?string $itemCommodityCode
     */
    #[JsonProperty('itemCommodityCode')]
    public ?string $itemCommodityCode;

    /**
     * @var float $itemCost Item or product price per unit.
     */
    #[JsonProperty('itemCost')]
    public float $itemCost;

    /**
     * @var ?string $itemDescription
     */
    #[JsonProperty('itemDescription')]
    public ?string $itemDescription;

    /**
     * @var ?int $itemMode Internal class of item or product: value '0' is only for invoices , '1' for bills and, '2' common for both.
     */
    #[JsonProperty('itemMode')]
    public ?int $itemMode;

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
     * @var ?int $itemQty Quantity of item or product.
     */
    #[JsonProperty('itemQty')]
    public ?int $itemQty;

    /**
     * @var ?float $itemTaxAmount Tax amount applied to item or product.
     */
    #[JsonProperty('itemTaxAmount')]
    public ?float $itemTaxAmount;

    /**
     * @var ?float $itemTaxRate Tax rate applied to item or product.
     */
    #[JsonProperty('itemTaxRate')]
    public ?float $itemTaxRate;

    /**
     * @var ?float $itemTotalAmount Total amount in item or product.
     */
    #[JsonProperty('itemTotalAmount')]
    public ?float $itemTotalAmount;

    /**
     * @var ?string $itemUnitOfMeasure
     */
    #[JsonProperty('itemUnitOfMeasure')]
    public ?string $itemUnitOfMeasure;

    /**
     * @param array{
     *   itemCost: float,
     *   itemCategories?: ?array<?string>,
     *   itemCommodityCode?: ?string,
     *   itemDescription?: ?string,
     *   itemMode?: ?int,
     *   itemProductCode?: ?string,
     *   itemProductName?: ?string,
     *   itemQty?: ?int,
     *   itemTaxAmount?: ?float,
     *   itemTaxRate?: ?float,
     *   itemTotalAmount?: ?float,
     *   itemUnitOfMeasure?: ?string,
     * } $values
     */
    public function __construct(
        array $values,
    ) {
        $this->itemCategories = $values['itemCategories'] ?? null;
        $this->itemCommodityCode = $values['itemCommodityCode'] ?? null;
        $this->itemCost = $values['itemCost'];
        $this->itemDescription = $values['itemDescription'] ?? null;
        $this->itemMode = $values['itemMode'] ?? null;
        $this->itemProductCode = $values['itemProductCode'] ?? null;
        $this->itemProductName = $values['itemProductName'] ?? null;
        $this->itemQty = $values['itemQty'] ?? null;
        $this->itemTaxAmount = $values['itemTaxAmount'] ?? null;
        $this->itemTaxRate = $values['itemTaxRate'] ?? null;
        $this->itemTotalAmount = $values['itemTotalAmount'] ?? null;
        $this->itemUnitOfMeasure = $values['itemUnitOfMeasure'] ?? null;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->toJson();
    }
}
