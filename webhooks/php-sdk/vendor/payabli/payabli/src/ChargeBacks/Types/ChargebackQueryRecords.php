<?php

namespace Payabli\ChargeBacks\Types;

use Payabli\Core\Json\JsonSerializableType;
use Payabli\Core\Json\JsonProperty;
use DateTime;
use Payabli\Core\Types\Date;
use Payabli\Types\QueryTransactionPayorData;
use Payabli\Types\QueryPaymentData;
use Payabli\Core\Types\ArrayType;
use Payabli\Types\TransactionQueryRecords;

class ChargebackQueryRecords extends JsonSerializableType
{
    /**
     * @var int $id Identifier of chargeback or return.
     */
    #[JsonProperty('Id')]
    public int $id;

    /**
     * @var DateTime $chargebackDate Date of chargeback in format YYYY-MM-DD or MM/DD/YYYY.
     */
    #[JsonProperty('ChargebackDate'), Date(Date::TYPE_DATETIME)]
    public DateTime $chargebackDate;

    /**
     * @var string $caseNumber Number of case assigned to the chargeback.
     */
    #[JsonProperty('CaseNumber')]
    public string $caseNumber;

    /**
     * @var string $reasonCode R code for returned ACH or custom code identifying the reason.
     */
    #[JsonProperty('ReasonCode')]
    public string $reasonCode;

    /**
     * @var string $reason Text describing the chargeback or ACH return reason.
     */
    #[JsonProperty('Reason')]
    public string $reason;

    /**
     * @var string $referenceNumber Processor reference number to the chargeback.
     */
    #[JsonProperty('ReferenceNumber')]
    public string $referenceNumber;

    /**
     * @var string $lastFour Last 4 digits of card or bank account involved in chargeback or return.
     */
    #[JsonProperty('LastFour')]
    public string $lastFour;

    /**
     * @var string $accountType
     */
    #[JsonProperty('AccountType')]
    public string $accountType;

    /**
     * Status for chargeback or ACH return
     *
     * - 0: Open (chargebacks only)
     * - 1: Pending (chargebacks only)
     * - 2: Closed-Won (chargebacks only)
     * - 3: Closed-Lost (chargebacks only)
     * - 4: ACH Return (ACH only)
     * - 5: ACH Dispute, Not Authorized (ACH only)
     *
     * @var int $status
     */
    #[JsonProperty('Status')]
    public int $status;

    /**
     * @var string $method Type of payment vehicle: **ach** or **card**.
     */
    #[JsonProperty('Method')]
    public string $method;

    /**
     * @var DateTime $createdAt Timestamp when the register was created, in UTC.
     */
    #[JsonProperty('CreatedAt'), Date(Date::TYPE_DATETIME)]
    public DateTime $createdAt;

    /**
     * @var DateTime $replyBy
     */
    #[JsonProperty('ReplyBy'), Date(Date::TYPE_DATETIME)]
    public DateTime $replyBy;

    /**
     * @var string $paymentTransId ReferenceId of the transaction in Payabli.
     */
    #[JsonProperty('PaymentTransId')]
    public string $paymentTransId;

    /**
     * @var ?int $scheduleReference Reference to the subscription originating the transaction.
     */
    #[JsonProperty('ScheduleReference')]
    public ?int $scheduleReference;

    /**
     * @var string $orderId
     */
    #[JsonProperty('OrderId')]
    public string $orderId;

    /**
     * @var ?float $netAmount Net amount in chargeback or ACH return.
     */
    #[JsonProperty('NetAmount')]
    public ?float $netAmount;

    /**
     * @var DateTime $transactionTime
     */
    #[JsonProperty('TransactionTime'), Date(Date::TYPE_DATETIME)]
    public DateTime $transactionTime;

    /**
     * @var QueryTransactionPayorData $customer
     */
    #[JsonProperty('Customer')]
    public QueryTransactionPayorData $customer;

    /**
     * @var QueryPaymentData $paymentData
     */
    #[JsonProperty('PaymentData')]
    public QueryPaymentData $paymentData;

    /**
     * @var string $paypointLegalname The paypoint's legal name.
     */
    #[JsonProperty('PaypointLegalname')]
    public string $paypointLegalname;

    /**
     * @var string $paypointDbaname The paypoint's DBA name.
     */
    #[JsonProperty('PaypointDbaname')]
    public string $paypointDbaname;

    /**
     * @var string $parentOrgName
     */
    #[JsonProperty('ParentOrgName')]
    public string $parentOrgName;

    /**
     * @var int $parentOrgId The ID of the parent organization.
     */
    #[JsonProperty('ParentOrgId')]
    public int $parentOrgId;

    /**
     * @var string $paypointEntryname The paypoint's entryname.
     */
    #[JsonProperty('PaypointEntryname')]
    public string $paypointEntryname;

    /**
     * @var array<ChargeBackResponse> $responses Chargeback response records.
     */
    #[JsonProperty('Responses'), ArrayType([ChargeBackResponse::class])]
    public array $responses;

    /**
     * @var TransactionQueryRecords $transaction
     */
    #[JsonProperty('Transaction')]
    public TransactionQueryRecords $transaction;

    /**
     * @var ?string $externalPaypointId
     */
    #[JsonProperty('externalPaypointID')]
    public ?string $externalPaypointId;

    /**
     * @var ?string $pageidentifier
     */
    #[JsonProperty('pageidentifier')]
    public ?string $pageidentifier;

    /**
     * @var array<ChargebackMessage> $messages Messages related to the chargeback.
     */
    #[JsonProperty('messages'), ArrayType([ChargebackMessage::class])]
    public array $messages;

    /**
     * @var string $serviceGroup Service group classification.
     */
    #[JsonProperty('ServiceGroup')]
    public string $serviceGroup;

    /**
     * @var string $disputeType Type of dispute classification.
     */
    #[JsonProperty('DisputeType')]
    public string $disputeType;

    /**
     * @var string $processorName Name of the payment processor.
     */
    #[JsonProperty('ProcessorName')]
    public string $processorName;

    /**
     * @param array{
     *   id: int,
     *   chargebackDate: DateTime,
     *   caseNumber: string,
     *   reasonCode: string,
     *   reason: string,
     *   referenceNumber: string,
     *   lastFour: string,
     *   accountType: string,
     *   status: int,
     *   method: string,
     *   createdAt: DateTime,
     *   replyBy: DateTime,
     *   paymentTransId: string,
     *   orderId: string,
     *   transactionTime: DateTime,
     *   customer: QueryTransactionPayorData,
     *   paymentData: QueryPaymentData,
     *   paypointLegalname: string,
     *   paypointDbaname: string,
     *   parentOrgName: string,
     *   parentOrgId: int,
     *   paypointEntryname: string,
     *   responses: array<ChargeBackResponse>,
     *   transaction: TransactionQueryRecords,
     *   messages: array<ChargebackMessage>,
     *   serviceGroup: string,
     *   disputeType: string,
     *   processorName: string,
     *   scheduleReference?: ?int,
     *   netAmount?: ?float,
     *   externalPaypointId?: ?string,
     *   pageidentifier?: ?string,
     * } $values
     */
    public function __construct(
        array $values,
    ) {
        $this->id = $values['id'];
        $this->chargebackDate = $values['chargebackDate'];
        $this->caseNumber = $values['caseNumber'];
        $this->reasonCode = $values['reasonCode'];
        $this->reason = $values['reason'];
        $this->referenceNumber = $values['referenceNumber'];
        $this->lastFour = $values['lastFour'];
        $this->accountType = $values['accountType'];
        $this->status = $values['status'];
        $this->method = $values['method'];
        $this->createdAt = $values['createdAt'];
        $this->replyBy = $values['replyBy'];
        $this->paymentTransId = $values['paymentTransId'];
        $this->scheduleReference = $values['scheduleReference'] ?? null;
        $this->orderId = $values['orderId'];
        $this->netAmount = $values['netAmount'] ?? null;
        $this->transactionTime = $values['transactionTime'];
        $this->customer = $values['customer'];
        $this->paymentData = $values['paymentData'];
        $this->paypointLegalname = $values['paypointLegalname'];
        $this->paypointDbaname = $values['paypointDbaname'];
        $this->parentOrgName = $values['parentOrgName'];
        $this->parentOrgId = $values['parentOrgId'];
        $this->paypointEntryname = $values['paypointEntryname'];
        $this->responses = $values['responses'];
        $this->transaction = $values['transaction'];
        $this->externalPaypointId = $values['externalPaypointId'] ?? null;
        $this->pageidentifier = $values['pageidentifier'] ?? null;
        $this->messages = $values['messages'];
        $this->serviceGroup = $values['serviceGroup'];
        $this->disputeType = $values['disputeType'];
        $this->processorName = $values['processorName'];
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->toJson();
    }
}
