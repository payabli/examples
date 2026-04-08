<?php

namespace Payabli\Types;

use Payabli\Core\Json\JsonSerializableType;
use Payabli\Core\Json\JsonProperty;
use Payabli\Core\Types\ArrayType;
use DateTime;
use Payabli\Core\Types\Date;

class BillDetailResponse extends JsonSerializableType
{
    /**
     * @var ?array<BillDetailsResponse> $bills Events associated to this transaction.
     */
    #[JsonProperty('Bills'), ArrayType([BillDetailsResponse::class])]
    public ?array $bills;

    /**
     * @var ?FileContent $checkData Object referencing to paper check image.
     */
    #[JsonProperty('CheckData')]
    public ?FileContent $checkData;

    /**
     * @var ?string $checkNumber Paper check number related to payout transaction.
     */
    #[JsonProperty('CheckNumber')]
    public ?string $checkNumber;

    /**
     * @var ?string $comments Any comment or description for payout transaction.
     */
    #[JsonProperty('Comments')]
    public ?string $comments;

    /**
     * @var ?DateTime $createdDate Timestamp when the payment was created, in UTC.
     */
    #[JsonProperty('CreatedDate'), Date(Date::TYPE_DATETIME)]
    public ?DateTime $createdDate;

    /**
     * @var ?DateTime $createdAt
     */
    #[JsonProperty('CreatedAt'), Date(Date::TYPE_DATETIME)]
    public ?DateTime $createdAt;

    /**
     * @var ?array<QueryTransactionEvents> $events Events associated to this transaction.
     */
    #[JsonProperty('Events'), ArrayType([QueryTransactionEvents::class])]
    public ?array $events;

    /**
     * @var ?float $feeAmount
     */
    #[JsonProperty('FeeAmount')]
    public ?float $feeAmount;

    /**
     * @var ?string $gateway
     */
    #[JsonProperty('Gateway')]
    public ?string $gateway;

    /**
     * @var ?int $idOut Identifier of payout transaction.
     */
    #[JsonProperty('IdOut')]
    public ?int $idOut;

    /**
     * @var ?DateTime $lastUpdated Timestamp when payment record was updated, in UTC.
     */
    #[JsonProperty('LastUpdated'), Date(Date::TYPE_DATETIME)]
    public ?DateTime $lastUpdated;

    /**
     * @var ?float $netAmount
     */
    #[JsonProperty('NetAmount')]
    public ?float $netAmount;

    /**
     * @var ?string $parentOrgName
     */
    #[JsonProperty('ParentOrgName')]
    public ?string $parentOrgName;

    /**
     * @var ?int $parentOrgId
     */
    #[JsonProperty('ParentOrgId')]
    public ?int $parentOrgId;

    /**
     * @var ?QueryPaymentData $paymentData
     */
    #[JsonProperty('PaymentData')]
    public ?QueryPaymentData $paymentData;

    /**
     * @var ?string $paymentGroup Unique identifier for group or batch containing the transaction.
     */
    #[JsonProperty('PaymentGroup')]
    public ?string $paymentGroup;

    /**
     * @var ?string $paymentId
     */
    #[JsonProperty('PaymentId')]
    public ?string $paymentId;

    /**
     * @var ?string $paymentMethod Method of payment applied to the transaction.
     */
    #[JsonProperty('PaymentMethod')]
    public ?string $paymentMethod;

    /**
     * @var ?string $paymentStatus Status of payout transaction.
     */
    #[JsonProperty('PaymentStatus')]
    public ?string $paymentStatus;

    /**
     * @var ?string $paypointDbaname
     */
    #[JsonProperty('PaypointDbaname')]
    public ?string $paypointDbaname;

    /**
     * @var ?string $paypointLegalname Paypoint legal name.
     */
    #[JsonProperty('PaypointLegalname')]
    public ?string $paypointLegalname;

    /**
     * @var ?string $source
     */
    #[JsonProperty('Source')]
    public ?string $source;

    /**
     * @var ?int $status Internal status of transaction.
     */
    #[JsonProperty('Status')]
    public ?int $status;

    /**
     * @var ?string $statusText Status of payout transaction.
     */
    #[JsonProperty('StatusText')]
    public ?string $statusText;

    /**
     * @var ?float $totalAmount Transaction total amount (including service fee or sub-charge).
     */
    #[JsonProperty('TotalAmount')]
    public ?float $totalAmount;

    /**
     * @var ?VendorQueryRecord $vendor Vendor related to the payout transaction.
     */
    #[JsonProperty('Vendor')]
    public ?VendorQueryRecord $vendor;

    /**
     * @var ?string $externalPaypointId
     */
    #[JsonProperty('externalPaypointID')]
    public ?string $externalPaypointId;

    /**
     * @var ?string $entryName
     */
    #[JsonProperty('EntryName')]
    public ?string $entryName;

    /**
     * @var ?string $batchId Identifier for the batch in which this transaction was processed. Used to track and reconcile batch-level operations.
     */
    #[JsonProperty('BatchId')]
    public ?string $batchId;

    /**
     * @var ?bool $hasVcardTransactions
     */
    #[JsonProperty('HasVcardTransactions')]
    public ?bool $hasVcardTransactions;

    /**
     * @var ?bool $isSameDayAch
     */
    #[JsonProperty('IsSameDayACH')]
    public ?bool $isSameDayAch;

    /**
     * @var ?int $scheduleId
     */
    #[JsonProperty('ScheduleId')]
    public ?int $scheduleId;

    /**
     * @var ?int $settlementStatus
     */
    #[JsonProperty('SettlementStatus')]
    public ?int $settlementStatus;

    /**
     * @var ?bool $riskFlagged
     */
    #[JsonProperty('RiskFlagged')]
    public ?bool $riskFlagged;

    /**
     * @var ?DateTime $riskFlaggedOn
     */
    #[JsonProperty('RiskFlaggedOn'), Date(Date::TYPE_DATETIME)]
    public ?DateTime $riskFlaggedOn;

    /**
     * @var ?string $riskStatus
     */
    #[JsonProperty('RiskStatus')]
    public ?string $riskStatus;

    /**
     * @var ?string $riskReason
     */
    #[JsonProperty('RiskReason')]
    public ?string $riskReason;

    /**
     * @var ?string $riskAction
     */
    #[JsonProperty('RiskAction')]
    public ?string $riskAction;

    /**
     * @var ?int $riskActionCode
     */
    #[JsonProperty('RiskActionCode')]
    public ?int $riskActionCode;

    /**
     * @param array{
     *   bills?: ?array<BillDetailsResponse>,
     *   checkData?: ?FileContent,
     *   checkNumber?: ?string,
     *   comments?: ?string,
     *   createdDate?: ?DateTime,
     *   createdAt?: ?DateTime,
     *   events?: ?array<QueryTransactionEvents>,
     *   feeAmount?: ?float,
     *   gateway?: ?string,
     *   idOut?: ?int,
     *   lastUpdated?: ?DateTime,
     *   netAmount?: ?float,
     *   parentOrgName?: ?string,
     *   parentOrgId?: ?int,
     *   paymentData?: ?QueryPaymentData,
     *   paymentGroup?: ?string,
     *   paymentId?: ?string,
     *   paymentMethod?: ?string,
     *   paymentStatus?: ?string,
     *   paypointDbaname?: ?string,
     *   paypointLegalname?: ?string,
     *   source?: ?string,
     *   status?: ?int,
     *   statusText?: ?string,
     *   totalAmount?: ?float,
     *   vendor?: ?VendorQueryRecord,
     *   externalPaypointId?: ?string,
     *   entryName?: ?string,
     *   batchId?: ?string,
     *   hasVcardTransactions?: ?bool,
     *   isSameDayAch?: ?bool,
     *   scheduleId?: ?int,
     *   settlementStatus?: ?int,
     *   riskFlagged?: ?bool,
     *   riskFlaggedOn?: ?DateTime,
     *   riskStatus?: ?string,
     *   riskReason?: ?string,
     *   riskAction?: ?string,
     *   riskActionCode?: ?int,
     * } $values
     */
    public function __construct(
        array $values = [],
    ) {
        $this->bills = $values['bills'] ?? null;
        $this->checkData = $values['checkData'] ?? null;
        $this->checkNumber = $values['checkNumber'] ?? null;
        $this->comments = $values['comments'] ?? null;
        $this->createdDate = $values['createdDate'] ?? null;
        $this->createdAt = $values['createdAt'] ?? null;
        $this->events = $values['events'] ?? null;
        $this->feeAmount = $values['feeAmount'] ?? null;
        $this->gateway = $values['gateway'] ?? null;
        $this->idOut = $values['idOut'] ?? null;
        $this->lastUpdated = $values['lastUpdated'] ?? null;
        $this->netAmount = $values['netAmount'] ?? null;
        $this->parentOrgName = $values['parentOrgName'] ?? null;
        $this->parentOrgId = $values['parentOrgId'] ?? null;
        $this->paymentData = $values['paymentData'] ?? null;
        $this->paymentGroup = $values['paymentGroup'] ?? null;
        $this->paymentId = $values['paymentId'] ?? null;
        $this->paymentMethod = $values['paymentMethod'] ?? null;
        $this->paymentStatus = $values['paymentStatus'] ?? null;
        $this->paypointDbaname = $values['paypointDbaname'] ?? null;
        $this->paypointLegalname = $values['paypointLegalname'] ?? null;
        $this->source = $values['source'] ?? null;
        $this->status = $values['status'] ?? null;
        $this->statusText = $values['statusText'] ?? null;
        $this->totalAmount = $values['totalAmount'] ?? null;
        $this->vendor = $values['vendor'] ?? null;
        $this->externalPaypointId = $values['externalPaypointId'] ?? null;
        $this->entryName = $values['entryName'] ?? null;
        $this->batchId = $values['batchId'] ?? null;
        $this->hasVcardTransactions = $values['hasVcardTransactions'] ?? null;
        $this->isSameDayAch = $values['isSameDayAch'] ?? null;
        $this->scheduleId = $values['scheduleId'] ?? null;
        $this->settlementStatus = $values['settlementStatus'] ?? null;
        $this->riskFlagged = $values['riskFlagged'] ?? null;
        $this->riskFlaggedOn = $values['riskFlaggedOn'] ?? null;
        $this->riskStatus = $values['riskStatus'] ?? null;
        $this->riskReason = $values['riskReason'] ?? null;
        $this->riskAction = $values['riskAction'] ?? null;
        $this->riskActionCode = $values['riskActionCode'] ?? null;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->toJson();
    }
}
