<?php

namespace Payabli\Types;

use Payabli\Core\Json\JsonSerializableType;
use Payabli\Core\Json\JsonProperty;

class QuerySummaryNoAmt extends JsonSerializableType
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
     *   totalPages?: ?int,
     *   totalRecords?: ?int,
     * } $values
     */
    public function __construct(
        array $values = [],
    ) {
        $this->pageIdentifier = $values['pageIdentifier'] ?? null;
        $this->pageSize = $values['pageSize'] ?? null;
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
