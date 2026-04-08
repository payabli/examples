<?php

namespace Payabli\Types;

use Payabli\Core\Json\JsonSerializableType;
use Payabli\Core\Json\JsonProperty;

class QuerySummary extends JsonSerializableType
{
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
     * @var ?float $totalAmount Total amount for the records.
     */
    #[JsonProperty('totalAmount')]
    public ?float $totalAmount;

    /**
     * @var ?float $totalNetAmount Total net amount for the records.
     */
    #[JsonProperty('totalNetAmount')]
    public ?float $totalNetAmount;

    /**
     * @var ?int $totalPages
     */
    #[JsonProperty('totalPages')]
    public ?int $totalPages;

    /**
     * @var ?int $totalRecords
     */
    #[JsonProperty('totalRecords')]
    public ?int $totalRecords;

    /**
     * @param array{
     *   pageIdentifier?: ?string,
     *   pageSize?: ?int,
     *   totalAmount?: ?float,
     *   totalNetAmount?: ?float,
     *   totalPages?: ?int,
     *   totalRecords?: ?int,
     * } $values
     */
    public function __construct(
        array $values = [],
    ) {
        $this->pageIdentifier = $values['pageIdentifier'] ?? null;
        $this->pageSize = $values['pageSize'] ?? null;
        $this->totalAmount = $values['totalAmount'] ?? null;
        $this->totalNetAmount = $values['totalNetAmount'] ?? null;
        $this->totalPages = $values['totalPages'] ?? null;
        $this->totalRecords = $values['totalRecords'] ?? null;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->toJson();
    }
}
