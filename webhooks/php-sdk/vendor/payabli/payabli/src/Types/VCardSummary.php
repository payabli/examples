<?php

namespace Payabli\Types;

use Payabli\Core\Json\JsonSerializableType;
use Payabli\Core\Json\JsonProperty;

class VCardSummary extends JsonSerializableType
{
    /**
     * @var int $totalPages
     */
    #[JsonProperty('totalPages')]
    public int $totalPages;

    /**
     * @var int $totalRecords
     */
    #[JsonProperty('totalRecords')]
    public int $totalRecords;

    /**
     * @var float $totalAmount Total amount for the records.
     */
    #[JsonProperty('totalAmount')]
    public float $totalAmount;

    /**
     * @var int $totalactive Total number of active vCards.
     */
    #[JsonProperty('totalactive')]
    public int $totalactive;

    /**
     * @var float $totalamounteactive Total amount of active vCards.
     */
    #[JsonProperty('totalamounteactive')]
    public float $totalamounteactive;

    /**
     * @var float $totalbalanceactive Total balance of active vCards.
     */
    #[JsonProperty('totalbalanceactive')]
    public float $totalbalanceactive;

    /**
     * @var ?string $pageIdentifier
     */
    #[JsonProperty('pageIdentifier')]
    public ?string $pageIdentifier;

    /**
     * @var ?int $pageSize
     */
    #[JsonProperty('pageSize')]
    public ?int $pageSize;

    /**
     * @param array{
     *   totalPages: int,
     *   totalRecords: int,
     *   totalAmount: float,
     *   totalactive: int,
     *   totalamounteactive: float,
     *   totalbalanceactive: float,
     *   pageIdentifier?: ?string,
     *   pageSize?: ?int,
     * } $values
     */
    public function __construct(
        array $values,
    ) {
        $this->totalPages = $values['totalPages'];
        $this->totalRecords = $values['totalRecords'];
        $this->totalAmount = $values['totalAmount'];
        $this->totalactive = $values['totalactive'];
        $this->totalamounteactive = $values['totalamounteactive'];
        $this->totalbalanceactive = $values['totalbalanceactive'];
        $this->pageIdentifier = $values['pageIdentifier'] ?? null;
        $this->pageSize = $values['pageSize'] ?? null;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->toJson();
    }
}
