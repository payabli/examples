<?php

namespace Payabli\Types;

use Payabli\Core\Json\JsonSerializableType;
use Payabli\Core\Json\JsonProperty;

class QueryPayoutTransactionRecordsItemPaymentData extends JsonSerializableType
{
    /**
     * @var ?string $accountExp
     */
    #[JsonProperty('AccountExp')]
    public ?string $accountExp;

    /**
     * @var ?string $accountId
     */
    #[JsonProperty('accountId')]
    public ?string $accountId;

    /**
     * @var ?string $accountType
     */
    #[JsonProperty('AccountType')]
    public ?string $accountType;

    /**
     * @var ?string $accountZip
     */
    #[JsonProperty('AccountZip')]
    public ?string $accountZip;

    /**
     * @var ?string $bankAccount
     */
    #[JsonProperty('bankAccount')]
    public ?string $bankAccount;

    /**
     * @var ?BinData $binData
     */
    #[JsonProperty('binData')]
    public ?BinData $binData;

    /**
     * @var ?string $cloudSignatureData
     */
    #[JsonProperty('cloudSignatureData')]
    public ?string $cloudSignatureData;

    /**
     * @var ?string $cloudSignatureFormat
     */
    #[JsonProperty('cloudSignatureFormat')]
    public ?string $cloudSignatureFormat;

    /**
     * @var ?PayoutGatewayConnector $gatewayConnector
     */
    #[JsonProperty('gatewayConnector')]
    public ?PayoutGatewayConnector $gatewayConnector;

    /**
     * @var ?string $holderName Card or bank account holder name.
     */
    #[JsonProperty('HolderName')]
    public ?string $holderName;

    /**
     * @var ?string $initiator
     */
    #[JsonProperty('Initiator')]
    public ?string $initiator;

    /**
     * @var ?string $maskedAccount
     */
    #[JsonProperty('MaskedAccount')]
    public ?string $maskedAccount;

    /**
     * @var ?string $orderDescription
     */
    #[JsonProperty('orderDescription')]
    public ?string $orderDescription;

    /**
     * @var ?PaymentDetail $paymentDetails
     */
    #[JsonProperty('paymentDetails')]
    public ?PaymentDetail $paymentDetails;

    /**
     * @var ?string $payorData
     */
    #[JsonProperty('payorData')]
    public ?string $payorData;

    /**
     * @var ?string $sequence
     */
    #[JsonProperty('Sequence')]
    public ?string $sequence;

    /**
     * @var ?string $storedId Identifier of stored payment method used in transaction.
     */
    #[JsonProperty('StoredId')]
    public ?string $storedId;

    /**
     * @var ?string $storedMethodUsageType
     */
    #[JsonProperty('StoredMethodUsageType')]
    public ?string $storedMethodUsageType;

    /**
     * @param array{
     *   accountExp?: ?string,
     *   accountId?: ?string,
     *   accountType?: ?string,
     *   accountZip?: ?string,
     *   bankAccount?: ?string,
     *   binData?: ?BinData,
     *   cloudSignatureData?: ?string,
     *   cloudSignatureFormat?: ?string,
     *   gatewayConnector?: ?PayoutGatewayConnector,
     *   holderName?: ?string,
     *   initiator?: ?string,
     *   maskedAccount?: ?string,
     *   orderDescription?: ?string,
     *   paymentDetails?: ?PaymentDetail,
     *   payorData?: ?string,
     *   sequence?: ?string,
     *   storedId?: ?string,
     *   storedMethodUsageType?: ?string,
     * } $values
     */
    public function __construct(
        array $values = [],
    ) {
        $this->accountExp = $values['accountExp'] ?? null;
        $this->accountId = $values['accountId'] ?? null;
        $this->accountType = $values['accountType'] ?? null;
        $this->accountZip = $values['accountZip'] ?? null;
        $this->bankAccount = $values['bankAccount'] ?? null;
        $this->binData = $values['binData'] ?? null;
        $this->cloudSignatureData = $values['cloudSignatureData'] ?? null;
        $this->cloudSignatureFormat = $values['cloudSignatureFormat'] ?? null;
        $this->gatewayConnector = $values['gatewayConnector'] ?? null;
        $this->holderName = $values['holderName'] ?? null;
        $this->initiator = $values['initiator'] ?? null;
        $this->maskedAccount = $values['maskedAccount'] ?? null;
        $this->orderDescription = $values['orderDescription'] ?? null;
        $this->paymentDetails = $values['paymentDetails'] ?? null;
        $this->payorData = $values['payorData'] ?? null;
        $this->sequence = $values['sequence'] ?? null;
        $this->storedId = $values['storedId'] ?? null;
        $this->storedMethodUsageType = $values['storedMethodUsageType'] ?? null;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->toJson();
    }
}
