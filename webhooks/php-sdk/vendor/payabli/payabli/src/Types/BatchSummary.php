<?php

namespace Payabli\Types;

use Payabli\Core\Json\JsonSerializableType;
use Payabli\Core\Json\JsonProperty;

class BatchSummary extends JsonSerializableType
{
    /**
     * @var ?string $pageidentifier
     */
    #[JsonProperty('pageidentifier')]
    public ?string $pageidentifier;

    /**
     * @var ?int $pageSize Number of records on each response page.
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
     * @var ?int $totalPages Total number of pages in response.
     */
    #[JsonProperty('totalPages')]
    public ?int $totalPages;

    /**
     * @var ?int $totalRecords Total number of records in response.
     */
    #[JsonProperty('totalRecords')]
    public ?int $totalRecords;

    /**
     * @param array{
     *   pageidentifier?: ?string,
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
        $this->pageidentifier = $values['pageidentifier'] ?? null;
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
