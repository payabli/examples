<?php

namespace Payabli\QueryTypes\Types;

use Payabli\Core\Json\JsonSerializableType;
use Payabli\Core\Json\JsonProperty;

class BatchDetailResponseSummary extends JsonSerializableType
{
    /**
     * @var float $serviceFees
     */
    #[JsonProperty('serviceFees')]
    public float $serviceFees;

    /**
     * @var float $transferAmount
     */
    #[JsonProperty('transferAmount')]
    public float $transferAmount;

    /**
     * @var float $refunds
     */
    #[JsonProperty('refunds')]
    public float $refunds;

    /**
     * @var float $heldAmount
     */
    #[JsonProperty('heldAmount')]
    public float $heldAmount;

    /**
     * @var int $totalRecords
     */
    #[JsonProperty('totalRecords')]
    public int $totalRecords;

    /**
     * @var float $totalAmount
     */
    #[JsonProperty('totalAmount')]
    public float $totalAmount;

    /**
     * @var float $totalNetAmount
     */
    #[JsonProperty('totalNetAmount')]
    public float $totalNetAmount;

    /**
     * @var int $totalPages
     */
    #[JsonProperty('totalPages')]
    public int $totalPages;

    /**
     * @var int $pageSize
     */
    #[JsonProperty('pageSize')]
    public int $pageSize;

    /**
     * @var ?string $pageidentifier
     */
    #[JsonProperty('pageidentifier')]
    public ?string $pageidentifier;

    /**
     * @param array{
     *   serviceFees: float,
     *   transferAmount: float,
     *   refunds: float,
     *   heldAmount: float,
     *   totalRecords: int,
     *   totalAmount: float,
     *   totalNetAmount: float,
     *   totalPages: int,
     *   pageSize: int,
     *   pageidentifier?: ?string,
     * } $values
     */
    public function __construct(
        array $values,
    ) {
        $this->serviceFees = $values['serviceFees'];
        $this->transferAmount = $values['transferAmount'];
        $this->refunds = $values['refunds'];
        $this->heldAmount = $values['heldAmount'];
        $this->totalRecords = $values['totalRecords'];
        $this->totalAmount = $values['totalAmount'];
        $this->totalNetAmount = $values['totalNetAmount'];
        $this->totalPages = $values['totalPages'];
        $this->pageSize = $values['pageSize'];
        $this->pageidentifier = $values['pageidentifier'] ?? null;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->toJson();
    }
}
