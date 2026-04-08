<?php

namespace Payabli\Types;

use Payabli\Core\Json\JsonSerializableType;
use Payabli\Core\Json\JsonProperty;

class QueryPaymentData extends JsonSerializableType
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
     * @var ?BinData $binData
     */
    #[JsonProperty('binData')]
    public ?BinData $binData;

    /**
     * @var ?string $holderName
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
     * @var ?string $sequence
     */
    #[JsonProperty('Sequence')]
    public ?string $sequence;

    /**
     * @var ?string $signatureData
     */
    #[JsonProperty('SignatureData')]
    public ?string $signatureData;

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
     *   binData?: ?BinData,
     *   holderName?: ?string,
     *   initiator?: ?string,
     *   maskedAccount?: ?string,
     *   orderDescription?: ?string,
     *   paymentDetails?: ?PaymentDetail,
     *   sequence?: ?string,
     *   signatureData?: ?string,
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
        $this->binData = $values['binData'] ?? null;
        $this->holderName = $values['holderName'] ?? null;
        $this->initiator = $values['initiator'] ?? null;
        $this->maskedAccount = $values['maskedAccount'] ?? null;
        $this->orderDescription = $values['orderDescription'] ?? null;
        $this->paymentDetails = $values['paymentDetails'] ?? null;
        $this->sequence = $values['sequence'] ?? null;
        $this->signatureData = $values['signatureData'] ?? null;
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
