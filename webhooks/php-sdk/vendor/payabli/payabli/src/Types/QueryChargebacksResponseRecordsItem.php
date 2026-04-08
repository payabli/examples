<?php

namespace Payabli\Types;

use Payabli\Core\Json\JsonSerializableType;
use Payabli\Core\Json\JsonProperty;
use DateTime;
use Payabli\Core\Types\Date;

class QueryChargebacksResponseRecordsItem extends JsonSerializableType
{
    /**
     * @var ?string $accountType Type of account.
     */
    #[JsonProperty('AccountType')]
    public ?string $accountType;

    /**
     * @var ?string $caseNumber Case number of the chargeback.
     */
    #[JsonProperty('CaseNumber')]
    public ?string $caseNumber;

    /**
     * @var ?DateTime $chargebackDate Date of the chargeback.
     */
    #[JsonProperty('ChargebackDate'), Date(Date::TYPE_DATETIME)]
    public ?DateTime $chargebackDate;

    /**
     * @var ?DateTime $createdAt
     */
    #[JsonProperty('CreatedAt'), Date(Date::TYPE_DATETIME)]
    public ?DateTime $createdAt;

    /**
     * @var ?QueryTransactionPayorData $customer
     */
    #[JsonProperty('Customer')]
    public ?QueryTransactionPayorData $customer;

    /**
     * @var ?string $externalPaypointId
     */
    #[JsonProperty('externalPaypointID')]
    public ?string $externalPaypointId;

    /**
     * @var ?int $id Unique identifier of the record.
     */
    #[JsonProperty('Id')]
    public ?int $id;

    /**
     * @var ?string $lastFour Last four digits of the account number.
     */
    #[JsonProperty('LastFour')]
    public ?string $lastFour;

    /**
     * @var ?string $method Method of payment.
     */
    #[JsonProperty('Method')]
    public ?string $method;

    /**
     * @var ?float $netAmount Net amount after deductions.
     */
    #[JsonProperty('NetAmount')]
    public ?float $netAmount;

    /**
     * @var ?string $orderId
     */
    #[JsonProperty('OrderId')]
    public ?string $orderId;

    /**
     * @var ?string $pageidentifier
     */
    #[JsonProperty('pageidentifier')]
    public ?string $pageidentifier;

    /**
     * @var ?string $parentOrgName
     */
    #[JsonProperty('ParentOrgName')]
    public ?string $parentOrgName;

    /**
     * @var ?QueryPaymentData $paymentData Payment data associated with the transaction.
     */
    #[JsonProperty('PaymentData')]
    public ?QueryPaymentData $paymentData;

    /**
     * @var ?string $paymentTransId Transaction ID for the payment.
     */
    #[JsonProperty('PaymentTransId')]
    public ?string $paymentTransId;

    /**
     * @var ?string $paypointDbaname The 'Doing Business As' (DBA) name of the paypoint.
     */
    #[JsonProperty('PaypointDbaname')]
    public ?string $paypointDbaname;

    /**
     * @var ?string $paypointEntryname Entryname for the paypoint.
     */
    #[JsonProperty('PaypointEntryname')]
    public ?string $paypointEntryname;

    /**
     * @var ?string $paypointLegalname Legal name of the paypoint.
     */
    #[JsonProperty('PaypointLegalname')]
    public ?string $paypointLegalname;

    /**
     * @var ?string $reason Description of the reason for chargeback.
     */
    #[JsonProperty('Reason')]
    public ?string $reason;

    /**
     * @var ?string $reasonCode Code representing the reason for chargeback.
     */
    #[JsonProperty('ReasonCode')]
    public ?string $reasonCode;

    /**
     * @var ?string $referenceNumber Reference number for the transaction.
     */
    #[JsonProperty('ReferenceNumber')]
    public ?string $referenceNumber;

    /**
     * @var ?DateTime $replyBy
     */
    #[JsonProperty('ReplyBy'), Date(Date::TYPE_DATETIME)]
    public ?DateTime $replyBy;

    /**
     * @var ?string $responses Responses related to the transaction.
     */
    #[JsonProperty('Responses')]
    public ?string $responses;

    /**
     * @var ?int $scheduleReference Reference for any scheduled transactions.
     */
    #[JsonProperty('ScheduleReference')]
    public ?int $scheduleReference;

    /**
     * @var ?int $status Status of the transaction.
     */
    #[JsonProperty('Status')]
    public ?int $status;

    /**
     * @var ?TransactionQueryRecords $transaction
     */
    #[JsonProperty('Transaction')]
    public ?TransactionQueryRecords $transaction;

    /**
     * @var ?DateTime $transactionTime
     */
    #[JsonProperty('TransactionTime'), Date(Date::TYPE_DATETIME)]
    public ?DateTime $transactionTime;

    /**
     * @param array{
     *   accountType?: ?string,
     *   caseNumber?: ?string,
     *   chargebackDate?: ?DateTime,
     *   createdAt?: ?DateTime,
     *   customer?: ?QueryTransactionPayorData,
     *   externalPaypointId?: ?string,
     *   id?: ?int,
     *   lastFour?: ?string,
     *   method?: ?string,
     *   netAmount?: ?float,
     *   orderId?: ?string,
     *   pageidentifier?: ?string,
     *   parentOrgName?: ?string,
     *   paymentData?: ?QueryPaymentData,
     *   paymentTransId?: ?string,
     *   paypointDbaname?: ?string,
     *   paypointEntryname?: ?string,
     *   paypointLegalname?: ?string,
     *   reason?: ?string,
     *   reasonCode?: ?string,
     *   referenceNumber?: ?string,
     *   replyBy?: ?DateTime,
     *   responses?: ?string,
     *   scheduleReference?: ?int,
     *   status?: ?int,
     *   transaction?: ?TransactionQueryRecords,
     *   transactionTime?: ?DateTime,
     * } $values
     */
    public function __construct(
        array $values = [],
    ) {
        $this->accountType = $values['accountType'] ?? null;
        $this->caseNumber = $values['caseNumber'] ?? null;
        $this->chargebackDate = $values['chargebackDate'] ?? null;
        $this->createdAt = $values['createdAt'] ?? null;
        $this->customer = $values['customer'] ?? null;
        $this->externalPaypointId = $values['externalPaypointId'] ?? null;
        $this->id = $values['id'] ?? null;
        $this->lastFour = $values['lastFour'] ?? null;
        $this->method = $values['method'] ?? null;
        $this->netAmount = $values['netAmount'] ?? null;
        $this->orderId = $values['orderId'] ?? null;
        $this->pageidentifier = $values['pageidentifier'] ?? null;
        $this->parentOrgName = $values['parentOrgName'] ?? null;
        $this->paymentData = $values['paymentData'] ?? null;
        $this->paymentTransId = $values['paymentTransId'] ?? null;
        $this->paypointDbaname = $values['paypointDbaname'] ?? null;
        $this->paypointEntryname = $values['paypointEntryname'] ?? null;
        $this->paypointLegalname = $values['paypointLegalname'] ?? null;
        $this->reason = $values['reason'] ?? null;
        $this->reasonCode = $values['reasonCode'] ?? null;
        $this->referenceNumber = $values['referenceNumber'] ?? null;
        $this->replyBy = $values['replyBy'] ?? null;
        $this->responses = $values['responses'] ?? null;
        $this->scheduleReference = $values['scheduleReference'] ?? null;
        $this->status = $values['status'] ?? null;
        $this->transaction = $values['transaction'] ?? null;
        $this->transactionTime = $values['transactionTime'] ?? null;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->toJson();
    }
}
