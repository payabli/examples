<?php

namespace Payabli\Types;

use Payabli\Core\Json\JsonSerializableType;
use Payabli\Core\Json\JsonProperty;
use DateTime;
use Payabli\Core\Types\Date;
use Payabli\Core\Types\ArrayType;

class QueryResponseSettlementsRecordsItem extends JsonSerializableType
{
    /**
     * @var ?float $batchAmount The batch amount.
     */
    #[JsonProperty('BatchAmount')]
    public ?float $batchAmount;

    /**
     * @var ?string $batchNumber
     */
    #[JsonProperty('BatchNumber')]
    public ?string $batchNumber;

    /**
     * @var ?string $category
     */
    #[JsonProperty('Category')]
    public ?string $category;

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
     * @var ?DateTime $depositDate
     */
    #[JsonProperty('DepositDate'), Date(Date::TYPE_DATETIME)]
    public ?DateTime $depositDate;

    /**
     * @var ?DateTime $expectedDepositDate
     */
    #[JsonProperty('ExpectedDepositDate'), Date(Date::TYPE_DATETIME)]
    public ?DateTime $expectedDepositDate;

    /**
     * @var ?string $externalPaypointId
     */
    #[JsonProperty('externalPaypointID')]
    public ?string $externalPaypointId;

    /**
     * @var ?string $gatewayTransId Internal identifier used for processing.
     */
    #[JsonProperty('GatewayTransId')]
    public ?string $gatewayTransId;

    /**
     * @var ?int $id
     */
    #[JsonProperty('Id')]
    public ?int $id;

    /**
     * @var ?BillData $invoiceData
     */
    #[JsonProperty('invoiceData')]
    public ?BillData $invoiceData;

    /**
     *
     * Describes whether the transaction is being held or not.
     *
     * 1 - Transaction is held
     *
     * 0 - Transaction isn't being held
     *
     * @var ?int $isHold
     */
    #[JsonProperty('isHold')]
    public ?int $isHold;

    /**
     * @var ?string $maskedAccount
     */
    #[JsonProperty('MaskedAccount')]
    public ?string $maskedAccount;

    /**
     * @var ?string $method The payment method.
     */
    #[JsonProperty('Method')]
    public ?string $method;

    /**
     * @var ?float $netAmount Net amount paid.
     */
    #[JsonProperty('NetAmount')]
    public ?float $netAmount;

    /**
     * @var ?string $operation The operation performed.
     */
    #[JsonProperty('Operation')]
    public ?string $operation;

    /**
     * @var ?string $orderId
     */
    #[JsonProperty('OrderId')]
    public ?string $orderId;

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
     * @var ?string $paymentTransId The transaction ID for the payment.
     */
    #[JsonProperty('PaymentTransId')]
    public ?string $paymentTransId;

    /**
     * @var ?int $paymentTransStatus
     */
    #[JsonProperty('PaymentTransStatus')]
    public ?int $paymentTransStatus;

    /**
     * @var ?string $paypointDbaname Paypoint DBA name.
     */
    #[JsonProperty('PaypointDbaname')]
    public ?string $paypointDbaname;

    /**
     * @var ?string $paypointEntryname Paypoint entryname.
     */
    #[JsonProperty('PaypointEntryname')]
    public ?string $paypointEntryname;

    /**
     * @var ?string $paypointLegalname Paypoint legal name.
     */
    #[JsonProperty('PaypointLegalname')]
    public ?string $paypointLegalname;

    /**
     * @var ?QueryResponseData $responseData
     */
    #[JsonProperty('ResponseData')]
    public ?QueryResponseData $responseData;

    /**
     * @var ?int $scheduleReference Reference to the subscription originating the transaction.
     */
    #[JsonProperty('ScheduleReference')]
    public ?int $scheduleReference;

    /**
     * @var ?float $settledAmount The transaction amount.
     */
    #[JsonProperty('SettledAmount')]
    public ?float $settledAmount;

    /**
     * @var ?DateTime $settlementDate
     */
    #[JsonProperty('SettlementDate'), Date(Date::TYPE_DATETIME)]
    public ?DateTime $settlementDate;

    /**
     * @var ?string $source
     */
    #[JsonProperty('Source')]
    public ?string $source;

    /**
     * @var ?int $status
     */
    #[JsonProperty('Status')]
    public ?int $status;

    /**
     * @var ?array<QueryTransactionEvents> $transactionEvents Events associated with this transaction.
     */
    #[JsonProperty('TransactionEvents'), ArrayType([QueryTransactionEvents::class])]
    public ?array $transactionEvents;

    /**
     * @var ?DateTime $transactionTime
     */
    #[JsonProperty('TransactionTime'), Date(Date::TYPE_DATETIME)]
    public ?DateTime $transactionTime;

    /**
     * @var ?string $transMethod Payment method used: card or ach.
     */
    #[JsonProperty('TransMethod')]
    public ?string $transMethod;

    /**
     * @var ?string $type The transaction type: credit or debit.
     */
    #[JsonProperty('Type')]
    public ?string $type;

    /**
     * @param array{
     *   batchAmount?: ?float,
     *   batchNumber?: ?string,
     *   category?: ?string,
     *   createdAt?: ?DateTime,
     *   customer?: ?QueryTransactionPayorData,
     *   depositDate?: ?DateTime,
     *   expectedDepositDate?: ?DateTime,
     *   externalPaypointId?: ?string,
     *   gatewayTransId?: ?string,
     *   id?: ?int,
     *   invoiceData?: ?BillData,
     *   isHold?: ?int,
     *   maskedAccount?: ?string,
     *   method?: ?string,
     *   netAmount?: ?float,
     *   operation?: ?string,
     *   orderId?: ?string,
     *   parentOrgName?: ?string,
     *   paymentData?: ?QueryPaymentData,
     *   paymentTransId?: ?string,
     *   paymentTransStatus?: ?int,
     *   paypointDbaname?: ?string,
     *   paypointEntryname?: ?string,
     *   paypointLegalname?: ?string,
     *   responseData?: ?QueryResponseData,
     *   scheduleReference?: ?int,
     *   settledAmount?: ?float,
     *   settlementDate?: ?DateTime,
     *   source?: ?string,
     *   status?: ?int,
     *   transactionEvents?: ?array<QueryTransactionEvents>,
     *   transactionTime?: ?DateTime,
     *   transMethod?: ?string,
     *   type?: ?string,
     * } $values
     */
    public function __construct(
        array $values = [],
    ) {
        $this->batchAmount = $values['batchAmount'] ?? null;
        $this->batchNumber = $values['batchNumber'] ?? null;
        $this->category = $values['category'] ?? null;
        $this->createdAt = $values['createdAt'] ?? null;
        $this->customer = $values['customer'] ?? null;
        $this->depositDate = $values['depositDate'] ?? null;
        $this->expectedDepositDate = $values['expectedDepositDate'] ?? null;
        $this->externalPaypointId = $values['externalPaypointId'] ?? null;
        $this->gatewayTransId = $values['gatewayTransId'] ?? null;
        $this->id = $values['id'] ?? null;
        $this->invoiceData = $values['invoiceData'] ?? null;
        $this->isHold = $values['isHold'] ?? null;
        $this->maskedAccount = $values['maskedAccount'] ?? null;
        $this->method = $values['method'] ?? null;
        $this->netAmount = $values['netAmount'] ?? null;
        $this->operation = $values['operation'] ?? null;
        $this->orderId = $values['orderId'] ?? null;
        $this->parentOrgName = $values['parentOrgName'] ?? null;
        $this->paymentData = $values['paymentData'] ?? null;
        $this->paymentTransId = $values['paymentTransId'] ?? null;
        $this->paymentTransStatus = $values['paymentTransStatus'] ?? null;
        $this->paypointDbaname = $values['paypointDbaname'] ?? null;
        $this->paypointEntryname = $values['paypointEntryname'] ?? null;
        $this->paypointLegalname = $values['paypointLegalname'] ?? null;
        $this->responseData = $values['responseData'] ?? null;
        $this->scheduleReference = $values['scheduleReference'] ?? null;
        $this->settledAmount = $values['settledAmount'] ?? null;
        $this->settlementDate = $values['settlementDate'] ?? null;
        $this->source = $values['source'] ?? null;
        $this->status = $values['status'] ?? null;
        $this->transactionEvents = $values['transactionEvents'] ?? null;
        $this->transactionTime = $values['transactionTime'] ?? null;
        $this->transMethod = $values['transMethod'] ?? null;
        $this->type = $values['type'] ?? null;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->toJson();
    }
}
