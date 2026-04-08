<?php

namespace Payabli\QueryTypes\Types;

use Payabli\Core\Json\JsonSerializableType;
use Payabli\Core\Json\JsonProperty;

class QueryTransferSummary extends JsonSerializableType
{
    /**
     * @var ?float $achReturns ACH returns deducted from the batch.
     */
    #[JsonProperty('achReturns')]
    public ?float $achReturns;

    /**
     * @var ?float $adjustments Corrections applied to Billing & Fees charges.
     */
    #[JsonProperty('adjustments')]
    public ?float $adjustments;

    /**
     * @var ?float $billingFees Charges applied for transactions and services.
     */
    #[JsonProperty('billingFees')]
    public ?float $billingFees;

    /**
     * @var ?float $chargebacks Chargebacks deducted from batch.
     */
    #[JsonProperty('chargebacks')]
    public ?float $chargebacks;

    /**
     * @var ?float $grossTransferAmount The gross batch amount before deductions.
     */
    #[JsonProperty('grossTransferAmount')]
    public ?float $grossTransferAmount;

    /**
     * @var ?float $releaseAmount Previously held funds that have been released after a risk review.
     */
    #[JsonProperty('releaseAmount')]
    public ?float $releaseAmount;

    /**
     * @var ?float $thirdPartyPaid Payments captured in the batch cycle that are deposited separately. For example,  checks or cash payments recorded in the batch but not deposited via Payabli,  or card brands making a direct transfer in certain situations.
     */
    #[JsonProperty('thirdPartyPaid')]
    public ?float $thirdPartyPaid;

    /**
     * @var ?float $totalNetAmountTransfer The gross batch amount minus service fees.
     */
    #[JsonProperty('totalNetAmountTransfer')]
    public ?float $totalNetAmountTransfer;

    /**
     * @var ?float $serviceFees Service fees are any pass-through fees charged to the customer at the time of payment.  These aren't transferred to the merchant when the batch is transferred and funded.
     */
    #[JsonProperty('serviceFees')]
    public ?float $serviceFees;

    /**
     * The net batch amount is the gross batch amount minus any returns, refunds,
     * billing and fees items, chargebacks, adjustments, and third party payments.
     *
     * @var ?float $netBatchAmount
     */
    #[JsonProperty('netBatchAmount')]
    public ?float $netBatchAmount;

    /**
     * @var ?float $transferAmount The transfer amount is the net batch amount plus or minus any returns, refunds,  billing and fees items, chargebacks, adjustments, and third party payments.  This is the amount from the batch that is transferred to the merchant bank account.
     */
    #[JsonProperty('transferAmount')]
    public ?float $transferAmount;

    /**
     * @var ?float $refunds Refunds deducted from batch.
     */
    #[JsonProperty('refunds')]
    public ?float $refunds;

    /**
     * @var ?float $heldAmount Funds being held for fraud or risk concerns.
     */
    #[JsonProperty('heldAmount')]
    public ?float $heldAmount;

    /**
     * @var ?int $totalRecords Number of records in the response.
     */
    #[JsonProperty('totalRecords')]
    public ?int $totalRecords;

    /**
     * @var ?float $totalAmount The total sum of the transfers in the response.
     */
    #[JsonProperty('totalAmount')]
    public ?float $totalAmount;

    /**
     * @var ?float $totalNetAmount The total sum of the transfers in the response.
     */
    #[JsonProperty('totalNetAmount')]
    public ?float $totalNetAmount;

    /**
     * @var ?int $totalPages Number of pages in the response.
     */
    #[JsonProperty('totalPages')]
    public ?int $totalPages;

    /**
     * @var ?int $pageSize Number of records per page.
     */
    #[JsonProperty('pageSize')]
    public ?int $pageSize;

    /**
     * @var ?string $pageidentifier Auxiliary validation used internally by payment pages and components.
     */
    #[JsonProperty('pageidentifier')]
    public ?string $pageidentifier;

    /**
     * @param array{
     *   achReturns?: ?float,
     *   adjustments?: ?float,
     *   billingFees?: ?float,
     *   chargebacks?: ?float,
     *   grossTransferAmount?: ?float,
     *   releaseAmount?: ?float,
     *   thirdPartyPaid?: ?float,
     *   totalNetAmountTransfer?: ?float,
     *   serviceFees?: ?float,
     *   netBatchAmount?: ?float,
     *   transferAmount?: ?float,
     *   refunds?: ?float,
     *   heldAmount?: ?float,
     *   totalRecords?: ?int,
     *   totalAmount?: ?float,
     *   totalNetAmount?: ?float,
     *   totalPages?: ?int,
     *   pageSize?: ?int,
     *   pageidentifier?: ?string,
     * } $values
     */
    public function __construct(
        array $values = [],
    ) {
        $this->achReturns = $values['achReturns'] ?? null;
        $this->adjustments = $values['adjustments'] ?? null;
        $this->billingFees = $values['billingFees'] ?? null;
        $this->chargebacks = $values['chargebacks'] ?? null;
        $this->grossTransferAmount = $values['grossTransferAmount'] ?? null;
        $this->releaseAmount = $values['releaseAmount'] ?? null;
        $this->thirdPartyPaid = $values['thirdPartyPaid'] ?? null;
        $this->totalNetAmountTransfer = $values['totalNetAmountTransfer'] ?? null;
        $this->serviceFees = $values['serviceFees'] ?? null;
        $this->netBatchAmount = $values['netBatchAmount'] ?? null;
        $this->transferAmount = $values['transferAmount'] ?? null;
        $this->refunds = $values['refunds'] ?? null;
        $this->heldAmount = $values['heldAmount'] ?? null;
        $this->totalRecords = $values['totalRecords'] ?? null;
        $this->totalAmount = $values['totalAmount'] ?? null;
        $this->totalNetAmount = $values['totalNetAmount'] ?? null;
        $this->totalPages = $values['totalPages'] ?? null;
        $this->pageSize = $values['pageSize'] ?? null;
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
