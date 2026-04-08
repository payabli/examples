<?php

namespace Payabli\Types;

use Payabli\Core\Json\JsonSerializableType;
use DateTime;
use Payabli\Core\Json\JsonProperty;
use Payabli\Core\Types\Date;
use Payabli\Core\Types\ArrayType;
use Payabli\Core\Types\Union;

class LineItemQueryRecord extends JsonSerializableType
{
    /**
     * @var ?DateTime $createdAt Timestamp of when line item was created, in UTC.
     */
    #[JsonProperty('createdAt'), Date(Date::TYPE_DATETIME)]
    public ?DateTime $createdAt;

    /**
     * @var ?int $id Identifier of line item.
     */
    #[JsonProperty('id')]
    public ?int $id;

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
     * @var ?int $itemMode Internal class of item or product: value '0' is only for invoices , '1' for bills, and '2' common for both.
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
     * @var int $itemQty Quantity of item or product.
     */
    #[JsonProperty('itemQty')]
    public int $itemQty;

    /**
     * @var ?string $itemUnitOfMeasure
     */
    #[JsonProperty('itemUnitOfMeasure')]
    public ?string $itemUnitOfMeasure;

    /**
     * @var ?DateTime $lastUpdated Timestamp of when the line item was updated, in UTC.
     */
    #[JsonProperty('lastUpdated'), Date(Date::TYPE_DATETIME)]
    public ?DateTime $lastUpdated;

    /**
     * @var ?string $pageidentifier
     */
    #[JsonProperty('pageidentifier')]
    public ?string $pageidentifier;

    /**
     * @var ?string $parentOrgName The name of the paypoint's parent organization.
     */
    #[JsonProperty('ParentOrgName')]
    public ?string $parentOrgName;

    /**
     * @var ?string $paypointDbaname The paypoint's DBA name.
     */
    #[JsonProperty('PaypointDbaname')]
    public ?string $paypointDbaname;

    /**
     * @var ?string $paypointEntryname The paypoint's entryname (entrypoint) value.
     */
    #[JsonProperty('PaypointEntryname')]
    public ?string $paypointEntryname;

    /**
     * @var ?string $paypointLegalname The paypoint's legal name.
     */
    #[JsonProperty('PaypointLegalname')]
    public ?string $paypointLegalname;

    /**
     * @param array{
     *   itemCost: float,
     *   itemQty: int,
     *   createdAt?: ?DateTime,
     *   id?: ?int,
     *   itemCategories?: ?array<?string>,
     *   itemCommodityCode?: ?string,
     *   itemDescription?: ?string,
     *   itemMode?: ?int,
     *   itemProductCode?: ?string,
     *   itemProductName?: ?string,
     *   itemUnitOfMeasure?: ?string,
     *   lastUpdated?: ?DateTime,
     *   pageidentifier?: ?string,
     *   parentOrgName?: ?string,
     *   paypointDbaname?: ?string,
     *   paypointEntryname?: ?string,
     *   paypointLegalname?: ?string,
     * } $values
     */
    public function __construct(
        array $values,
    ) {
        $this->createdAt = $values['createdAt'] ?? null;
        $this->id = $values['id'] ?? null;
        $this->itemCategories = $values['itemCategories'] ?? null;
        $this->itemCommodityCode = $values['itemCommodityCode'] ?? null;
        $this->itemCost = $values['itemCost'];
        $this->itemDescription = $values['itemDescription'] ?? null;
        $this->itemMode = $values['itemMode'] ?? null;
        $this->itemProductCode = $values['itemProductCode'] ?? null;
        $this->itemProductName = $values['itemProductName'] ?? null;
        $this->itemQty = $values['itemQty'];
        $this->itemUnitOfMeasure = $values['itemUnitOfMeasure'] ?? null;
        $this->lastUpdated = $values['lastUpdated'] ?? null;
        $this->pageidentifier = $values['pageidentifier'] ?? null;
        $this->parentOrgName = $values['parentOrgName'] ?? null;
        $this->paypointDbaname = $values['paypointDbaname'] ?? null;
        $this->paypointEntryname = $values['paypointEntryname'] ?? null;
        $this->paypointLegalname = $values['paypointLegalname'] ?? null;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->toJson();
    }
}
