<?php

namespace Payabli\Types;

use Payabli\Core\Json\JsonSerializableType;
use Payabli\Core\Json\JsonProperty;

class TransferSummary extends JsonSerializableType
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
     * @var int $pageSize
     */
    #[JsonProperty('pageSize')]
    public int $pageSize;

    /**
     * @param array{
     *   totalPages: int,
     *   totalRecords: int,
     *   pageSize: int,
     * } $values
     */
    public function __construct(
        array $values,
    ) {
        $this->totalPages = $values['totalPages'];
        $this->totalRecords = $values['totalRecords'];
        $this->pageSize = $values['pageSize'];
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->toJson();
    }
}
