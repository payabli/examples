<?php

namespace Payabli\Types;

use Payabli\Core\Json\JsonSerializableType;
use DateTime;
use Payabli\Core\Json\JsonProperty;
use Payabli\Core\Types\Date;
use Payabli\Core\Types\ArrayType;

class SubscriptionQueryRecords extends JsonSerializableType
{
    /**
     * @var ?DateTime $createdAt Timestamp of when the subscription ws created, in UTC.
     */
    #[JsonProperty('CreatedAt'), Date(Date::TYPE_DATETIME)]
    public ?DateTime $createdAt;

    /**
     * @var ?QueryTransactionPayorData $customer
     */
    #[JsonProperty('Customer')]
    public ?QueryTransactionPayorData $customer;

    /**
     * @var ?DateTime $endDate The subscription's end date.
     */
    #[JsonProperty('EndDate'), Date(Date::TYPE_DATETIME)]
    public ?DateTime $endDate;

    /**
     * @var ?int $entrypageId
     */
    #[JsonProperty('EntrypageId')]
    public ?int $entrypageId;

    /**
     * @var ?string $externalPaypointId
     */
    #[JsonProperty('ExternalPaypointID')]
    public ?string $externalPaypointId;

    /**
     * @var ?float $feeAmount Fee applied to the subscription.
     */
    #[JsonProperty('FeeAmount')]
    public ?float $feeAmount;

    /**
     * @var ?string $frequency The subscription's frequency.
     */
    #[JsonProperty('Frequency')]
    public ?string $frequency;

    /**
     * @var ?int $idSub The subscription's ID.
     */
    #[JsonProperty('IdSub')]
    public ?int $idSub;

    /**
     * @var ?BillData $invoiceData
     */
    #[JsonProperty('InvoiceData')]
    public ?BillData $invoiceData;

    /**
     * @var ?DateTime $lastRun The last time the subscription was processed.
     */
    #[JsonProperty('LastRun'), Date(Date::TYPE_DATETIME)]
    public ?DateTime $lastRun;

    /**
     * @var ?DateTime $lastUpdated The last date and time the subscription was updated.
     */
    #[JsonProperty('LastUpdated'), Date(Date::TYPE_DATETIME)]
    public ?DateTime $lastUpdated;

    /**
     * @var ?int $leftCycles The number of cycles the subscription has left.
     */
    #[JsonProperty('LeftCycles')]
    public ?int $leftCycles;

    /**
     * @var ?string $method The subscription's payment method.
     */
    #[JsonProperty('Method')]
    public ?string $method;

    /**
     * @var ?float $netAmount The subscription amount, minus any fees.
     */
    #[JsonProperty('NetAmount')]
    public ?float $netAmount;

    /**
     * @var ?DateTime $nextDate The next date the subscription will be processed.
     */
    #[JsonProperty('NextDate'), Date(Date::TYPE_DATETIME)]
    public ?DateTime $nextDate;

    /**
     * @var ?string $parentOrgName
     */
    #[JsonProperty('ParentOrgName')]
    public ?string $parentOrgName;

    /**
     * @var ?QueryPaymentData $paymentData
     */
    #[JsonProperty('PaymentData')]
    public ?QueryPaymentData $paymentData;

    /**
     * @var ?string $paypointDbaname The paypoint's DBA name.
     */
    #[JsonProperty('PaypointDbaname')]
    public ?string $paypointDbaname;

    /**
     * @var ?string $paypointEntryname The paypoint's entryname.
     */
    #[JsonProperty('PaypointEntryname')]
    public ?string $paypointEntryname;

    /**
     * @var ?int $paypointId
     */
    #[JsonProperty('PaypointId')]
    public ?int $paypointId;

    /**
     * @var ?string $paypointLegalname The paypoint's legal name.
     */
    #[JsonProperty('PaypointLegalname')]
    public ?string $paypointLegalname;

    /**
     * @var ?int $planId Payment plan ID.
     */
    #[JsonProperty('PlanId')]
    public ?int $planId;

    /**
     * @var ?string $source
     */
    #[JsonProperty('Source')]
    public ?string $source;

    /**
     * @var ?DateTime $startDate The subscription start date.
     */
    #[JsonProperty('StartDate'), Date(Date::TYPE_DATETIME)]
    public ?DateTime $startDate;

    /**
     * @var ?array<GeneralEvents> $subEvents Events associated with the subscription.
     */
    #[JsonProperty('SubEvents'), ArrayType([GeneralEvents::class])]
    public ?array $subEvents;

    /**
     * The subscription's status.
     * - 0: Paused
     * - 1: Active
     *
     * @var ?int $subStatus
     */
    #[JsonProperty('SubStatus')]
    public ?int $subStatus;

    /**
     * @var ?float $totalAmount The subscription amount, including any fees.
     */
    #[JsonProperty('TotalAmount')]
    public ?float $totalAmount;

    /**
     * @var ?int $totalCycles The total number of cycles the subscription is set to run.
     */
    #[JsonProperty('TotalCycles')]
    public ?int $totalCycles;

    /**
     * @var ?bool $untilCancelled When `true`, the subscription has no explicit end date and will run until canceled.
     */
    #[JsonProperty('UntilCancelled')]
    public ?bool $untilCancelled;

    /**
     * @param array{
     *   createdAt?: ?DateTime,
     *   customer?: ?QueryTransactionPayorData,
     *   endDate?: ?DateTime,
     *   entrypageId?: ?int,
     *   externalPaypointId?: ?string,
     *   feeAmount?: ?float,
     *   frequency?: ?string,
     *   idSub?: ?int,
     *   invoiceData?: ?BillData,
     *   lastRun?: ?DateTime,
     *   lastUpdated?: ?DateTime,
     *   leftCycles?: ?int,
     *   method?: ?string,
     *   netAmount?: ?float,
     *   nextDate?: ?DateTime,
     *   parentOrgName?: ?string,
     *   paymentData?: ?QueryPaymentData,
     *   paypointDbaname?: ?string,
     *   paypointEntryname?: ?string,
     *   paypointId?: ?int,
     *   paypointLegalname?: ?string,
     *   planId?: ?int,
     *   source?: ?string,
     *   startDate?: ?DateTime,
     *   subEvents?: ?array<GeneralEvents>,
     *   subStatus?: ?int,
     *   totalAmount?: ?float,
     *   totalCycles?: ?int,
     *   untilCancelled?: ?bool,
     * } $values
     */
    public function __construct(
        array $values = [],
    ) {
        $this->createdAt = $values['createdAt'] ?? null;
        $this->customer = $values['customer'] ?? null;
        $this->endDate = $values['endDate'] ?? null;
        $this->entrypageId = $values['entrypageId'] ?? null;
        $this->externalPaypointId = $values['externalPaypointId'] ?? null;
        $this->feeAmount = $values['feeAmount'] ?? null;
        $this->frequency = $values['frequency'] ?? null;
        $this->idSub = $values['idSub'] ?? null;
        $this->invoiceData = $values['invoiceData'] ?? null;
        $this->lastRun = $values['lastRun'] ?? null;
        $this->lastUpdated = $values['lastUpdated'] ?? null;
        $this->leftCycles = $values['leftCycles'] ?? null;
        $this->method = $values['method'] ?? null;
        $this->netAmount = $values['netAmount'] ?? null;
        $this->nextDate = $values['nextDate'] ?? null;
        $this->parentOrgName = $values['parentOrgName'] ?? null;
        $this->paymentData = $values['paymentData'] ?? null;
        $this->paypointDbaname = $values['paypointDbaname'] ?? null;
        $this->paypointEntryname = $values['paypointEntryname'] ?? null;
        $this->paypointId = $values['paypointId'] ?? null;
        $this->paypointLegalname = $values['paypointLegalname'] ?? null;
        $this->planId = $values['planId'] ?? null;
        $this->source = $values['source'] ?? null;
        $this->startDate = $values['startDate'] ?? null;
        $this->subEvents = $values['subEvents'] ?? null;
        $this->subStatus = $values['subStatus'] ?? null;
        $this->totalAmount = $values['totalAmount'] ?? null;
        $this->totalCycles = $values['totalCycles'] ?? null;
        $this->untilCancelled = $values['untilCancelled'] ?? null;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->toJson();
    }
}
