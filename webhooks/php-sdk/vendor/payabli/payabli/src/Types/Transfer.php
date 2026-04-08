<?php

namespace Payabli\Types;

use Payabli\Core\Json\JsonSerializableType;
use Payabli\Core\Json\JsonProperty;
use Payabli\Core\Types\ArrayType;

class Transfer extends JsonSerializableType
{
    /**
     * @var int $transferId The transfer ID.
     */
    #[JsonProperty('transferId')]
    public int $transferId;

    /**
     * @var int $paypointId
     */
    #[JsonProperty('paypointId')]
    public int $paypointId;

    /**
     * @var string $batchNumber
     */
    #[JsonProperty('batchNumber')]
    public string $batchNumber;

    /**
     * @var ?string $batchCurrency The currency of the batch, either USD or CAD.
     */
    #[JsonProperty('batchCurrency')]
    public ?string $batchCurrency;

    /**
     * @var ?int $batchRecords Number of records in the batch.
     */
    #[JsonProperty('batchRecords')]
    public ?int $batchRecords;

    /**
     * @var string $transferIdentifier
     */
    #[JsonProperty('transferIdentifier')]
    public string $transferIdentifier;

    /**
     * @var int $batchId The ID of the batch the transfer belongs to.
     */
    #[JsonProperty('batchId')]
    public int $batchId;

    /**
     * @var ?string $paypointEntryName The paypoint entryname.
     */
    #[JsonProperty('paypointEntryName')]
    public ?string $paypointEntryName;

    /**
     * @var ?string $paypointLegalName The paypoint legal name.
     */
    #[JsonProperty('paypointLegalName')]
    public ?string $paypointLegalName;

    /**
     * @var ?string $paypointDbaName The paypoint DBA name.
     */
    #[JsonProperty('paypointDbaName')]
    public ?string $paypointDbaName;

    /**
     * @var ?string $paypointLogo The paypoint logo URL.
     */
    #[JsonProperty('paypointLogo')]
    public ?string $paypointLogo;

    /**
     * @var ?string $parentOrgName The parent organization name.
     */
    #[JsonProperty('parentOrgName')]
    public ?string $parentOrgName;

    /**
     * @var ?int $parentOrgId The parent organization ID.
     */
    #[JsonProperty('parentOrgId')]
    public ?int $parentOrgId;

    /**
     * @var ?string $parentOrgEntryName The parent organization entryname.
     */
    #[JsonProperty('parentOrgEntryName')]
    public ?string $parentOrgEntryName;

    /**
     * @var ?string $parentOrgLogo The parent organization logo URL.
     */
    #[JsonProperty('parentOrgLogo')]
    public ?string $parentOrgLogo;

    /**
     * @var ?string $externalPaypointId The external paypoint ID.
     */
    #[JsonProperty('externalPaypointID')]
    public ?string $externalPaypointId;

    /**
     * @var ?TransferBankAccount $bankAccount Bank account information for the transfer.
     */
    #[JsonProperty('bankAccount')]
    public ?TransferBankAccount $bankAccount;

    /**
     * @var string $transferDate Date when the transfer occurred.
     */
    #[JsonProperty('transferDate')]
    public string $transferDate;

    /**
     * @var string $processor The payment processor used for the transfer.
     */
    #[JsonProperty('processor')]
    public string $processor;

    /**
     * @var int $transferStatus The current status of the transfer.
     */
    #[JsonProperty('transferStatus')]
    public int $transferStatus;

    /**
     * @var float $grossAmount Gross batch is the total amount of the payments grouped in the batch. This amount includes service fees.
     */
    #[JsonProperty('grossAmount')]
    public float $grossAmount;

    /**
     * @var float $chargeBackAmount Amount of chargebacks to be deducted from batch.
     */
    #[JsonProperty('chargeBackAmount')]
    public float $chargeBackAmount;

    /**
     * @var float $returnedAmount Amount of ACH returns to be deducted from batch.
     */
    #[JsonProperty('returnedAmount')]
    public float $returnedAmount;

    /**
     * @var float $holdAmount Amount being held for fraud or risk concerns.
     */
    #[JsonProperty('holdAmount')]
    public float $holdAmount;

    /**
     * @var float $releasedAmount Amount of previously held funds that have been released after a risk review.
     */
    #[JsonProperty('releasedAmount')]
    public float $releasedAmount;

    /**
     * @var float $billingFeesAmount Amount of charges and fees applied for services and transactions.
     */
    #[JsonProperty('billingFeesAmount')]
    public float $billingFeesAmount;

    /**
     * @var float $thirdPartyPaidAmount Amount of payments captured in the batch cycle that are deposited separately. For example, checks or cash payments recorded in the batch but not deposited via Payabli, or card brands making a direct transfer in certain situations.
     */
    #[JsonProperty('thirdPartyPaidAmount')]
    public float $thirdPartyPaidAmount;

    /**
     * @var float $adjustmentsAmount Amount of corrections applied to Billing & Fees charges.
     */
    #[JsonProperty('adjustmentsAmount')]
    public float $adjustmentsAmount;

    /**
     * @var float $netTransferAmount The net transfer amount after all deductions and additions.
     */
    #[JsonProperty('netTransferAmount')]
    public float $netTransferAmount;

    /**
     * @var ?array<GeneralEvents> $eventsData List of events associated with the transfer.
     */
    #[JsonProperty('eventsData'), ArrayType([GeneralEvents::class])]
    public ?array $eventsData;

    /**
     * @var ?array<TransferMessage> $messages List of messages related to the transfer.
     */
    #[JsonProperty('messages'), ArrayType([TransferMessage::class])]
    public ?array $messages;

    /**
     * @param array{
     *   transferId: int,
     *   paypointId: int,
     *   batchNumber: string,
     *   transferIdentifier: string,
     *   batchId: int,
     *   transferDate: string,
     *   processor: string,
     *   transferStatus: int,
     *   grossAmount: float,
     *   chargeBackAmount: float,
     *   returnedAmount: float,
     *   holdAmount: float,
     *   releasedAmount: float,
     *   billingFeesAmount: float,
     *   thirdPartyPaidAmount: float,
     *   adjustmentsAmount: float,
     *   netTransferAmount: float,
     *   batchCurrency?: ?string,
     *   batchRecords?: ?int,
     *   paypointEntryName?: ?string,
     *   paypointLegalName?: ?string,
     *   paypointDbaName?: ?string,
     *   paypointLogo?: ?string,
     *   parentOrgName?: ?string,
     *   parentOrgId?: ?int,
     *   parentOrgEntryName?: ?string,
     *   parentOrgLogo?: ?string,
     *   externalPaypointId?: ?string,
     *   bankAccount?: ?TransferBankAccount,
     *   eventsData?: ?array<GeneralEvents>,
     *   messages?: ?array<TransferMessage>,
     * } $values
     */
    public function __construct(
        array $values,
    ) {
        $this->transferId = $values['transferId'];
        $this->paypointId = $values['paypointId'];
        $this->batchNumber = $values['batchNumber'];
        $this->batchCurrency = $values['batchCurrency'] ?? null;
        $this->batchRecords = $values['batchRecords'] ?? null;
        $this->transferIdentifier = $values['transferIdentifier'];
        $this->batchId = $values['batchId'];
        $this->paypointEntryName = $values['paypointEntryName'] ?? null;
        $this->paypointLegalName = $values['paypointLegalName'] ?? null;
        $this->paypointDbaName = $values['paypointDbaName'] ?? null;
        $this->paypointLogo = $values['paypointLogo'] ?? null;
        $this->parentOrgName = $values['parentOrgName'] ?? null;
        $this->parentOrgId = $values['parentOrgId'] ?? null;
        $this->parentOrgEntryName = $values['parentOrgEntryName'] ?? null;
        $this->parentOrgLogo = $values['parentOrgLogo'] ?? null;
        $this->externalPaypointId = $values['externalPaypointId'] ?? null;
        $this->bankAccount = $values['bankAccount'] ?? null;
        $this->transferDate = $values['transferDate'];
        $this->processor = $values['processor'];
        $this->transferStatus = $values['transferStatus'];
        $this->grossAmount = $values['grossAmount'];
        $this->chargeBackAmount = $values['chargeBackAmount'];
        $this->returnedAmount = $values['returnedAmount'];
        $this->holdAmount = $values['holdAmount'];
        $this->releasedAmount = $values['releasedAmount'];
        $this->billingFeesAmount = $values['billingFeesAmount'];
        $this->thirdPartyPaidAmount = $values['thirdPartyPaidAmount'];
        $this->adjustmentsAmount = $values['adjustmentsAmount'];
        $this->netTransferAmount = $values['netTransferAmount'];
        $this->eventsData = $values['eventsData'] ?? null;
        $this->messages = $values['messages'] ?? null;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->toJson();
    }
}
