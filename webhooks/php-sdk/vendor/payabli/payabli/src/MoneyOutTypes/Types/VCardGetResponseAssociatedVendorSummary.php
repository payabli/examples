<?php

namespace Payabli\MoneyOutTypes\Types;

use Payabli\Core\Json\JsonSerializableType;
use Payabli\Core\Json\JsonProperty;

/**
 * Summary of vendor's billing and transaction status.
 */
class VCardGetResponseAssociatedVendorSummary extends JsonSerializableType
{
    /**
     * @var ?int $activeBills Number of active bills.
     */
    #[JsonProperty('ActiveBills')]
    public ?int $activeBills;

    /**
     * @var ?int $pendingBills Number of bills pending approval or payment.
     */
    #[JsonProperty('PendingBills')]
    public ?int $pendingBills;

    /**
     * @var ?int $inTransitBills Number of bills in transit.
     */
    #[JsonProperty('InTransitBills')]
    public ?int $inTransitBills;

    /**
     * @var ?int $paidBills Number of bills that have been paid.
     */
    #[JsonProperty('PaidBills')]
    public ?int $paidBills;

    /**
     * @var ?int $overdueBills Number of bills that are overdue.
     */
    #[JsonProperty('OverdueBills')]
    public ?int $overdueBills;

    /**
     * @var ?int $approvedBills Number of bills that have been approved.
     */
    #[JsonProperty('ApprovedBills')]
    public ?int $approvedBills;

    /**
     * @var ?int $disapprovedBills Number of bills that have been disapproved.
     */
    #[JsonProperty('DisapprovedBills')]
    public ?int $disapprovedBills;

    /**
     * @var ?int $totalBills Total number of bills.
     */
    #[JsonProperty('TotalBills')]
    public ?int $totalBills;

    /**
     * @var ?float $activeBillsAmount Total amount of active bills.
     */
    #[JsonProperty('ActiveBillsAmount')]
    public ?float $activeBillsAmount;

    /**
     * @var ?float $pendingBillsAmount Total amount of pending bills.
     */
    #[JsonProperty('PendingBillsAmount')]
    public ?float $pendingBillsAmount;

    /**
     * @var ?float $inTransitBillsAmount Total amount of bills in transit.
     */
    #[JsonProperty('InTransitBillsAmount')]
    public ?float $inTransitBillsAmount;

    /**
     * @var ?float $paidBillsAmount Total amount of paid bills.
     */
    #[JsonProperty('PaidBillsAmount')]
    public ?float $paidBillsAmount;

    /**
     * @var ?float $overdueBillsAmount Total amount of overdue bills.
     */
    #[JsonProperty('OverdueBillsAmount')]
    public ?float $overdueBillsAmount;

    /**
     * @var ?float $approvedBillsAmount Total amount of approved bills.
     */
    #[JsonProperty('ApprovedBillsAmount')]
    public ?float $approvedBillsAmount;

    /**
     * @var ?float $disapprovedBillsAmount Total amount of rejected bills.
     */
    #[JsonProperty('DisapprovedBillsAmount')]
    public ?float $disapprovedBillsAmount;

    /**
     * @var ?float $totalBillsAmount Total amount of all bills.
     */
    #[JsonProperty('TotalBillsAmount')]
    public ?float $totalBillsAmount;

    /**
     * @param array{
     *   activeBills?: ?int,
     *   pendingBills?: ?int,
     *   inTransitBills?: ?int,
     *   paidBills?: ?int,
     *   overdueBills?: ?int,
     *   approvedBills?: ?int,
     *   disapprovedBills?: ?int,
     *   totalBills?: ?int,
     *   activeBillsAmount?: ?float,
     *   pendingBillsAmount?: ?float,
     *   inTransitBillsAmount?: ?float,
     *   paidBillsAmount?: ?float,
     *   overdueBillsAmount?: ?float,
     *   approvedBillsAmount?: ?float,
     *   disapprovedBillsAmount?: ?float,
     *   totalBillsAmount?: ?float,
     * } $values
     */
    public function __construct(
        array $values = [],
    ) {
        $this->activeBills = $values['activeBills'] ?? null;
        $this->pendingBills = $values['pendingBills'] ?? null;
        $this->inTransitBills = $values['inTransitBills'] ?? null;
        $this->paidBills = $values['paidBills'] ?? null;
        $this->overdueBills = $values['overdueBills'] ?? null;
        $this->approvedBills = $values['approvedBills'] ?? null;
        $this->disapprovedBills = $values['disapprovedBills'] ?? null;
        $this->totalBills = $values['totalBills'] ?? null;
        $this->activeBillsAmount = $values['activeBillsAmount'] ?? null;
        $this->pendingBillsAmount = $values['pendingBillsAmount'] ?? null;
        $this->inTransitBillsAmount = $values['inTransitBillsAmount'] ?? null;
        $this->paidBillsAmount = $values['paidBillsAmount'] ?? null;
        $this->overdueBillsAmount = $values['overdueBillsAmount'] ?? null;
        $this->approvedBillsAmount = $values['approvedBillsAmount'] ?? null;
        $this->disapprovedBillsAmount = $values['disapprovedBillsAmount'] ?? null;
        $this->totalBillsAmount = $values['totalBillsAmount'] ?? null;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->toJson();
    }
}
