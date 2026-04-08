<?php

namespace Payabli\Types;

use Payabli\Core\Json\JsonSerializableType;
use Payabli\Core\Json\JsonProperty;

class QueryPayoutTransactionSummary extends JsonSerializableType
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
     * @var ?float $totalAmount
     */
    #[JsonProperty('totalAmount')]
    public ?float $totalAmount;

    /**
     * @var ?int $totalAuthorized
     */
    #[JsonProperty('totalAuthorized')]
    public ?int $totalAuthorized;

    /**
     * @var ?float $totalAuthorizedAmount
     */
    #[JsonProperty('totalAuthorizedAmount')]
    public ?float $totalAuthorizedAmount;

    /**
     * @var ?int $totalCanceled
     */
    #[JsonProperty('totalCanceled')]
    public ?int $totalCanceled;

    /**
     * @var ?float $totalCanceledAmount
     */
    #[JsonProperty('totalCanceledAmount')]
    public ?float $totalCanceledAmount;

    /**
     * @var ?int $totalCaptured
     */
    #[JsonProperty('totalCaptured')]
    public ?int $totalCaptured;

    /**
     * @var ?float $totalCapturedAmount
     */
    #[JsonProperty('totalCapturedAmount')]
    public ?float $totalCapturedAmount;

    /**
     * @var ?float $totalNetAmount
     */
    #[JsonProperty('totalNetAmount')]
    public ?float $totalNetAmount;

    /**
     * @var ?int $totalOpen
     */
    #[JsonProperty('totalOpen')]
    public ?int $totalOpen;

    /**
     * @var ?float $totalOpenAmount
     */
    #[JsonProperty('totalOpenAmount')]
    public ?float $totalOpenAmount;

    /**
     * @var ?int $totalPages
     */
    #[JsonProperty('totalPages')]
    public ?int $totalPages;

    /**
     * @var ?int $totalPaid
     */
    #[JsonProperty('totalPaid')]
    public ?int $totalPaid;

    /**
     * @var ?float $totalPaidAmount
     */
    #[JsonProperty('totalPaidAmount')]
    public ?float $totalPaidAmount;

    /**
     * @var ?int $totalOnHold Total number of transactions that are currently on hold.
     */
    #[JsonProperty('totalOnHold')]
    public ?int $totalOnHold;

    /**
     * @var ?float $totalOnHoldAmount Total amount of transactions that are currently on hold.
     */
    #[JsonProperty('totalOnHoldAmount')]
    public ?float $totalOnHoldAmount;

    /**
     * @var ?int $totalProcessing
     */
    #[JsonProperty('totalProcessing')]
    public ?int $totalProcessing;

    /**
     * @var ?float $totalProcessingAmount
     */
    #[JsonProperty('totalProcessingAmount')]
    public ?float $totalProcessingAmount;

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
     *   totalAuthorized?: ?int,
     *   totalAuthorizedAmount?: ?float,
     *   totalCanceled?: ?int,
     *   totalCanceledAmount?: ?float,
     *   totalCaptured?: ?int,
     *   totalCapturedAmount?: ?float,
     *   totalNetAmount?: ?float,
     *   totalOpen?: ?int,
     *   totalOpenAmount?: ?float,
     *   totalPages?: ?int,
     *   totalPaid?: ?int,
     *   totalPaidAmount?: ?float,
     *   totalOnHold?: ?int,
     *   totalOnHoldAmount?: ?float,
     *   totalProcessing?: ?int,
     *   totalProcessingAmount?: ?float,
     *   totalRecords?: ?int,
     * } $values
     */
    public function __construct(
        array $values = [],
    ) {
        $this->pageIdentifier = $values['pageIdentifier'] ?? null;
        $this->pageSize = $values['pageSize'] ?? null;
        $this->totalAmount = $values['totalAmount'] ?? null;
        $this->totalAuthorized = $values['totalAuthorized'] ?? null;
        $this->totalAuthorizedAmount = $values['totalAuthorizedAmount'] ?? null;
        $this->totalCanceled = $values['totalCanceled'] ?? null;
        $this->totalCanceledAmount = $values['totalCanceledAmount'] ?? null;
        $this->totalCaptured = $values['totalCaptured'] ?? null;
        $this->totalCapturedAmount = $values['totalCapturedAmount'] ?? null;
        $this->totalNetAmount = $values['totalNetAmount'] ?? null;
        $this->totalOpen = $values['totalOpen'] ?? null;
        $this->totalOpenAmount = $values['totalOpenAmount'] ?? null;
        $this->totalPages = $values['totalPages'] ?? null;
        $this->totalPaid = $values['totalPaid'] ?? null;
        $this->totalPaidAmount = $values['totalPaidAmount'] ?? null;
        $this->totalOnHold = $values['totalOnHold'] ?? null;
        $this->totalOnHoldAmount = $values['totalOnHoldAmount'] ?? null;
        $this->totalProcessing = $values['totalProcessing'] ?? null;
        $this->totalProcessingAmount = $values['totalProcessingAmount'] ?? null;
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
