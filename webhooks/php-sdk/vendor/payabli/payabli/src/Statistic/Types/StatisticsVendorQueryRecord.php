<?php

namespace Payabli\Statistic\Types;

use Payabli\Core\Json\JsonSerializableType;
use Payabli\Core\Json\JsonProperty;

class StatisticsVendorQueryRecord extends JsonSerializableType
{
    /**
     * @var string $statX Statistical grouping identifier
     */
    #[JsonProperty('statX')]
    public string $statX;

    /**
     * @var int $active Number of active transactions
     */
    #[JsonProperty('active')]
    public int $active;

    /**
     * @var float $activeVolume Volume of active transactions
     */
    #[JsonProperty('activeVolume')]
    public float $activeVolume;

    /**
     * @var int $sentToApproval Number of transactions sent to approval
     */
    #[JsonProperty('sentToApproval')]
    public int $sentToApproval;

    /**
     * @var float $sentToApprovalVolume Volume of transactions sent to approval
     */
    #[JsonProperty('sentToApprovalVolume')]
    public float $sentToApprovalVolume;

    /**
     * @var int $toApproval Number of transactions to approval
     */
    #[JsonProperty('toApproval')]
    public int $toApproval;

    /**
     * @var float $toApprovalVolume Volume of transactions to approval
     */
    #[JsonProperty('toApprovalVolume')]
    public float $toApprovalVolume;

    /**
     * @var int $approved Number of approved transactions
     */
    #[JsonProperty('approved')]
    public int $approved;

    /**
     * @var float $approvedVolume Volume of approved transactions
     */
    #[JsonProperty('approvedVolume')]
    public float $approvedVolume;

    /**
     * @var int $disapproved Number of disapproved transactions
     */
    #[JsonProperty('disapproved')]
    public int $disapproved;

    /**
     * @var float $disapprovedVolume Volume of disapproved transactions
     */
    #[JsonProperty('disapprovedVolume')]
    public float $disapprovedVolume;

    /**
     * @var int $cancelled Number of cancelled transactions
     */
    #[JsonProperty('cancelled')]
    public int $cancelled;

    /**
     * @var float $cancelledVolume Volume of cancelled transactions
     */
    #[JsonProperty('cancelledVolume')]
    public float $cancelledVolume;

    /**
     * @var int $inTransit Number of transactions in transit
     */
    #[JsonProperty('inTransit')]
    public int $inTransit;

    /**
     * @var float $inTransitVolume Volume of transactions in transit
     */
    #[JsonProperty('inTransitVolume')]
    public float $inTransitVolume;

    /**
     * @var int $paid Number of paid transactions
     */
    #[JsonProperty('paid')]
    public int $paid;

    /**
     * @var float $paidVolume Volume of paid transactions
     */
    #[JsonProperty('paidVolume')]
    public float $paidVolume;

    /**
     * @param array{
     *   statX: string,
     *   active: int,
     *   activeVolume: float,
     *   sentToApproval: int,
     *   sentToApprovalVolume: float,
     *   toApproval: int,
     *   toApprovalVolume: float,
     *   approved: int,
     *   approvedVolume: float,
     *   disapproved: int,
     *   disapprovedVolume: float,
     *   cancelled: int,
     *   cancelledVolume: float,
     *   inTransit: int,
     *   inTransitVolume: float,
     *   paid: int,
     *   paidVolume: float,
     * } $values
     */
    public function __construct(
        array $values,
    ) {
        $this->statX = $values['statX'];
        $this->active = $values['active'];
        $this->activeVolume = $values['activeVolume'];
        $this->sentToApproval = $values['sentToApproval'];
        $this->sentToApprovalVolume = $values['sentToApprovalVolume'];
        $this->toApproval = $values['toApproval'];
        $this->toApprovalVolume = $values['toApprovalVolume'];
        $this->approved = $values['approved'];
        $this->approvedVolume = $values['approvedVolume'];
        $this->disapproved = $values['disapproved'];
        $this->disapprovedVolume = $values['disapprovedVolume'];
        $this->cancelled = $values['cancelled'];
        $this->cancelledVolume = $values['cancelledVolume'];
        $this->inTransit = $values['inTransit'];
        $this->inTransitVolume = $values['inTransitVolume'];
        $this->paid = $values['paid'];
        $this->paidVolume = $values['paidVolume'];
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->toJson();
    }
}
