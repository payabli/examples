<?php

namespace Payabli\Types;

use Payabli\Core\Json\JsonSerializableType;
use Payabli\Core\Json\JsonProperty;

class QueryResponseSettlementsSummary extends JsonSerializableType
{
    /**
     * @var ?float $heldAmount Funds being held for fraud or risk concerns.
     */
    #[JsonProperty('heldAmount')]
    public ?float $heldAmount;

    /**
     * @var ?string $pageidentifier
     */
    #[JsonProperty('pageidentifier')]
    public ?string $pageidentifier;

    /**
     * @var ?int $pageSize Number of records per page.
     */
    #[JsonProperty('pageSize')]
    public ?int $pageSize;

    /**
     * @var ?float $refunds Total refunds deducted from the transfer.
     */
    #[JsonProperty('refunds')]
    public ?float $refunds;

    /**
     * @var ?float $serviceFees Service fees are any pass-through fees charged to the customer at the time of payment. These aren't transferred to the merchant when the batch is transferred and funded.
     */
    #[JsonProperty('serviceFees')]
    public ?float $serviceFees;

    /**
     * @var ?float $totalAmount The total sum of the settlements in the response.
     */
    #[JsonProperty('totalAmount')]
    public ?float $totalAmount;

    /**
     * @var ?float $totalNetAmount The total sum of the settlements in the response.
     */
    #[JsonProperty('totalNetAmount')]
    public ?float $totalNetAmount;

    /**
     * @var ?int $totalPages Number of pages in the response.
     */
    #[JsonProperty('totalPages')]
    public ?int $totalPages;

    /**
     * @var ?int $totalRecords Number of records in the response.
     */
    #[JsonProperty('totalRecords')]
    public ?int $totalRecords;

    /**
     * @var ?float $transferAmount The transfer amount is the net batch amount plus or minus any returns, refunds, billing and fees items, chargebacks, adjustments, and third party payments. This is the amount from the batch that's transferred to the merchant bank account.
     */
    #[JsonProperty('transferAmount')]
    public ?float $transferAmount;

    /**
     * @param array{
     *   heldAmount?: ?float,
     *   pageidentifier?: ?string,
     *   pageSize?: ?int,
     *   refunds?: ?float,
     *   serviceFees?: ?float,
     *   totalAmount?: ?float,
     *   totalNetAmount?: ?float,
     *   totalPages?: ?int,
     *   totalRecords?: ?int,
     *   transferAmount?: ?float,
     * } $values
     */
    public function __construct(
        array $values = [],
    ) {
        $this->heldAmount = $values['heldAmount'] ?? null;
        $this->pageidentifier = $values['pageidentifier'] ?? null;
        $this->pageSize = $values['pageSize'] ?? null;
        $this->refunds = $values['refunds'] ?? null;
        $this->serviceFees = $values['serviceFees'] ?? null;
        $this->totalAmount = $values['totalAmount'] ?? null;
        $this->totalNetAmount = $values['totalNetAmount'] ?? null;
        $this->totalPages = $values['totalPages'] ?? null;
        $this->totalRecords = $values['totalRecords'] ?? null;
        $this->transferAmount = $values['transferAmount'] ?? null;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->toJson();
    }
}
