<?php

namespace Payabli\Types;

use Payabli\Core\Json\JsonSerializableType;
use Payabli\Core\Json\JsonProperty;
use DateTime;
use Payabli\Core\Types\Date;

class VCardRecord extends JsonSerializableType
{
    /**
     * @var ?bool $vcardSent When `true`, the vCard has been sent.
     */
    #[JsonProperty('vcardSent')]
    public ?bool $vcardSent;

    /**
     * @var ?string $cardToken
     */
    #[JsonProperty('cardToken')]
    public ?string $cardToken;

    /**
     * @var ?string $cardNumber The vCard number.
     */
    #[JsonProperty('cardNumber')]
    public ?string $cardNumber;

    /**
     * @var ?string $cvc The vCard CVC number.
     */
    #[JsonProperty('cvc')]
    public ?string $cvc;

    /**
     * @var ?string $expirationDate Expiration date in format YYYY-MM-DD. The minimum time to expire is 3 months, maximum is 3 years. If not provided, the default is 6 months.
     */
    #[JsonProperty('expirationDate')]
    public ?string $expirationDate;

    /**
     * @var ?string $status
     */
    #[JsonProperty('status')]
    public ?string $status;

    /**
     * @var ?float $amount The vCard amount.
     */
    #[JsonProperty('amount')]
    public ?float $amount;

    /**
     * @var ?float $currentBalance The vCard's current balance.
     */
    #[JsonProperty('currentBalance')]
    public ?float $currentBalance;

    /**
     * @var ?float $expenseLimit
     */
    #[JsonProperty('expenseLimit')]
    public ?float $expenseLimit;

    /**
     * @var ?string $expenseLimitPeriod
     */
    #[JsonProperty('expenseLimitPeriod')]
    public ?string $expenseLimitPeriod;

    /**
     * @var ?int $maxNumberOfUses
     */
    #[JsonProperty('maxNumberOfUses')]
    public ?int $maxNumberOfUses;

    /**
     * @var ?int $currentNumberOfUses
     */
    #[JsonProperty('currentNumberOfUses')]
    public ?int $currentNumberOfUses;

    /**
     * @var ?bool $exactAmount
     */
    #[JsonProperty('exactAmount')]
    public ?bool $exactAmount;

    /**
     * @var ?string $mcc MCC assigned to vCard.
     */
    #[JsonProperty('mcc')]
    public ?string $mcc;

    /**
     * @var ?string $tcc TCC assigned to vCard.
     */
    #[JsonProperty('tcc')]
    public ?string $tcc;

    /**
     * @var ?string $misc1 Custom field 1.
     */
    #[JsonProperty('misc1')]
    public ?string $misc1;

    /**
     * @var ?string $misc2 Custom field 2.
     */
    #[JsonProperty('misc2')]
    public ?string $misc2;

    /**
     * @var ?DateTime $dateCreated
     */
    #[JsonProperty('dateCreated'), Date(Date::TYPE_DATETIME)]
    public ?DateTime $dateCreated;

    /**
     * @var ?DateTime $dateModified
     */
    #[JsonProperty('dateModified'), Date(Date::TYPE_DATETIME)]
    public ?DateTime $dateModified;

    /**
     * @var ?AssociatedVendor $associatedVendor
     */
    #[JsonProperty('associatedVendor')]
    public ?AssociatedVendor $associatedVendor;

    /**
     * @var ?CustomerData $associatedCustomer
     */
    #[JsonProperty('associatedCustomer')]
    public ?CustomerData $associatedCustomer;

    /**
     * @var ?string $parentOrgName
     */
    #[JsonProperty('ParentOrgName')]
    public ?string $parentOrgName;

    /**
     * @var ?string $paypointDbaname The paypoint's DBA name.
     */
    #[JsonProperty('PaypointDbaname')]
    public ?string $paypointDbaname;

    /**
     * @var ?string $paypointLegalname The paypoint's legal name.
     */
    #[JsonProperty('PaypointLegalname')]
    public ?string $paypointLegalname;

    /**
     * @var ?string $paypointEntryname The paypoint's entry name (entrypoint).
     */
    #[JsonProperty('PaypointEntryname')]
    public ?string $paypointEntryname;

    /**
     * @var ?string $externalPaypointId
     */
    #[JsonProperty('externalPaypointID')]
    public ?string $externalPaypointId;

    /**
     * @var ?int $paypointId The paypoint's unique identifier.
     */
    #[JsonProperty('paypointId')]
    public ?int $paypointId;

    /**
     * @param array{
     *   vcardSent?: ?bool,
     *   cardToken?: ?string,
     *   cardNumber?: ?string,
     *   cvc?: ?string,
     *   expirationDate?: ?string,
     *   status?: ?string,
     *   amount?: ?float,
     *   currentBalance?: ?float,
     *   expenseLimit?: ?float,
     *   expenseLimitPeriod?: ?string,
     *   maxNumberOfUses?: ?int,
     *   currentNumberOfUses?: ?int,
     *   exactAmount?: ?bool,
     *   mcc?: ?string,
     *   tcc?: ?string,
     *   misc1?: ?string,
     *   misc2?: ?string,
     *   dateCreated?: ?DateTime,
     *   dateModified?: ?DateTime,
     *   associatedVendor?: ?AssociatedVendor,
     *   associatedCustomer?: ?CustomerData,
     *   parentOrgName?: ?string,
     *   paypointDbaname?: ?string,
     *   paypointLegalname?: ?string,
     *   paypointEntryname?: ?string,
     *   externalPaypointId?: ?string,
     *   paypointId?: ?int,
     * } $values
     */
    public function __construct(
        array $values = [],
    ) {
        $this->vcardSent = $values['vcardSent'] ?? null;
        $this->cardToken = $values['cardToken'] ?? null;
        $this->cardNumber = $values['cardNumber'] ?? null;
        $this->cvc = $values['cvc'] ?? null;
        $this->expirationDate = $values['expirationDate'] ?? null;
        $this->status = $values['status'] ?? null;
        $this->amount = $values['amount'] ?? null;
        $this->currentBalance = $values['currentBalance'] ?? null;
        $this->expenseLimit = $values['expenseLimit'] ?? null;
        $this->expenseLimitPeriod = $values['expenseLimitPeriod'] ?? null;
        $this->maxNumberOfUses = $values['maxNumberOfUses'] ?? null;
        $this->currentNumberOfUses = $values['currentNumberOfUses'] ?? null;
        $this->exactAmount = $values['exactAmount'] ?? null;
        $this->mcc = $values['mcc'] ?? null;
        $this->tcc = $values['tcc'] ?? null;
        $this->misc1 = $values['misc1'] ?? null;
        $this->misc2 = $values['misc2'] ?? null;
        $this->dateCreated = $values['dateCreated'] ?? null;
        $this->dateModified = $values['dateModified'] ?? null;
        $this->associatedVendor = $values['associatedVendor'] ?? null;
        $this->associatedCustomer = $values['associatedCustomer'] ?? null;
        $this->parentOrgName = $values['parentOrgName'] ?? null;
        $this->paypointDbaname = $values['paypointDbaname'] ?? null;
        $this->paypointLegalname = $values['paypointLegalname'] ?? null;
        $this->paypointEntryname = $values['paypointEntryname'] ?? null;
        $this->externalPaypointId = $values['externalPaypointId'] ?? null;
        $this->paypointId = $values['paypointId'] ?? null;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->toJson();
    }
}
