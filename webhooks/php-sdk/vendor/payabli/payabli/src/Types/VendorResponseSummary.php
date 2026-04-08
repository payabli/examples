<?php

namespace Payabli\Types;

use Payabli\Core\Json\JsonSerializableType;
use Payabli\Core\Json\JsonProperty;

/**
 * Vendor bill summary statistics
 */
class VendorResponseSummary extends JsonSerializableType
{
    /**
     * @var ?int $activeBills
     */
    #[JsonProperty('ActiveBills')]
    public ?int $activeBills;

    /**
     * @var ?int $pendingBills
     */
    #[JsonProperty('PendingBills')]
    public ?int $pendingBills;

    /**
     * @var ?int $inTransitBills
     */
    #[JsonProperty('InTransitBills')]
    public ?int $inTransitBills;

    /**
     * @var ?int $paidBills
     */
    #[JsonProperty('PaidBills')]
    public ?int $paidBills;

    /**
     * @var ?int $overdueBills
     */
    #[JsonProperty('OverdueBills')]
    public ?int $overdueBills;

    /**
     * @var ?int $approvedBills
     */
    #[JsonProperty('ApprovedBills')]
    public ?int $approvedBills;

    /**
     * @var ?int $disapprovedBills
     */
    #[JsonProperty('DisapprovedBills')]
    public ?int $disapprovedBills;

    /**
     * @var ?int $totalBills
     */
    #[JsonProperty('TotalBills')]
    public ?int $totalBills;

    /**
     * @var ?float $activeBillsAmount
     */
    #[JsonProperty('ActiveBillsAmount')]
    public ?float $activeBillsAmount;

    /**
     * @var ?float $pendingBillsAmount
     */
    #[JsonProperty('PendingBillsAmount')]
    public ?float $pendingBillsAmount;

    /**
     * @var ?float $inTransitBillsAmount
     */
    #[JsonProperty('InTransitBillsAmount')]
    public ?float $inTransitBillsAmount;

    /**
     * @var ?float $paidBillsAmount
     */
    #[JsonProperty('PaidBillsAmount')]
    public ?float $paidBillsAmount;

    /**
     * @var ?float $overdueBillsAmount
     */
    #[JsonProperty('OverdueBillsAmount')]
    public ?float $overdueBillsAmount;

    /**
     * @var ?float $approvedBillsAmount
     */
    #[JsonProperty('ApprovedBillsAmount')]
    public ?float $approvedBillsAmount;

    /**
     * @var ?float $disapprovedBillsAmount
     */
    #[JsonProperty('DisapprovedBillsAmount')]
    public ?float $disapprovedBillsAmount;

    /**
     * @var ?float $totalBillsAmount
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
