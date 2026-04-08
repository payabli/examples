<?php

namespace Payabli\MoneyOutTypes\Types;

use Payabli\Core\Json\JsonSerializableType;
use Payabli\Core\Json\JsonProperty;

class VCardGetResponse extends JsonSerializableType
{
    /**
     * @var ?bool $vcardSent Indicates if the virtual card was sent.
     */
    #[JsonProperty('vcardSent')]
    public ?bool $vcardSent;

    /**
     * @var ?string $cardToken A unique token identifier for the card.
     */
    #[JsonProperty('cardToken')]
    public ?string $cardToken;

    /**
     * @var ?string $cardNumber The masked number of the card.
     */
    #[JsonProperty('cardNumber')]
    public ?string $cardNumber;

    /**
     * @var ?string $cvc Masked Card Verification Code.
     */
    #[JsonProperty('cvc')]
    public ?string $cvc;

    /**
     * @var ?string $expirationDate The expiration date of the card.
     */
    #[JsonProperty('expirationDate')]
    public ?string $expirationDate;

    /**
     * @var ?string $status The current status of the card.
     */
    #[JsonProperty('status')]
    public ?string $status;

    /**
     * @var ?float $amount The initial amount loaded on the card.
     */
    #[JsonProperty('amount')]
    public ?float $amount;

    /**
     * @var ?float $currentBalance The current balance available on the card.
     */
    #[JsonProperty('currentBalance')]
    public ?float $currentBalance;

    /**
     * @var ?float $expenseLimit The set limit for expenses.
     */
    #[JsonProperty('expenseLimit')]
    public ?float $expenseLimit;

    /**
     * @var ?string $expenseLimitPeriod The period for the expense limit.
     */
    #[JsonProperty('expenseLimitPeriod')]
    public ?string $expenseLimitPeriod;

    /**
     * @var ?int $maxNumberOfUses Maximum number of uses allowed for the card.
     */
    #[JsonProperty('maxNumberOfUses')]
    public ?int $maxNumberOfUses;

    /**
     * @var ?int $currentNumberOfUses The current number of times the card has been used.
     */
    #[JsonProperty('currentNumberOfUses')]
    public ?int $currentNumberOfUses;

    /**
     * @var ?bool $exactAmount Indicates if only the exact amount is allowed for transactions.
     */
    #[JsonProperty('exactAmount')]
    public ?bool $exactAmount;

    /**
     * @var ?string $mcc Merchant Category Code, if applicable.
     */
    #[JsonProperty('mcc')]
    public ?string $mcc;

    /**
     * @var ?string $tcc Transaction Category Code, if applicable.
     */
    #[JsonProperty('tcc')]
    public ?string $tcc;

    /**
     * @var ?string $misc1 A miscellaneous field for additional information.
     */
    #[JsonProperty('misc1')]
    public ?string $misc1;

    /**
     * @var ?string $misc2 Another miscellaneous field for extra information.
     */
    #[JsonProperty('misc2')]
    public ?string $misc2;

    /**
     * @var ?string $dateCreated The creation date of the record.
     */
    #[JsonProperty('dateCreated')]
    public ?string $dateCreated;

    /**
     * @var ?string $dateModified The last modified date of the record.
     */
    #[JsonProperty('dateModified')]
    public ?string $dateModified;

    /**
     * @var ?VCardGetResponseAssociatedVendor $associatedVendor Information about the associated vendor.
     */
    #[JsonProperty('associatedVendor')]
    public ?VCardGetResponseAssociatedVendor $associatedVendor;

    /**
     * @var ?string $associatedCustomer Information about the associated customer, if applicable.
     */
    #[JsonProperty('associatedCustomer')]
    public ?string $associatedCustomer;

    /**
     * @var ?string $parentOrgName Name of the parent organization.
     */
    #[JsonProperty('ParentOrgName')]
    public ?string $parentOrgName;

    /**
     * @var ?string $paypointDbaname The 'Doing Business As' name of the Paypoint.
     */
    #[JsonProperty('PaypointDbaname')]
    public ?string $paypointDbaname;

    /**
     * @var ?string $paypointLegalname The legal name of the Paypoint.
     */
    #[JsonProperty('PaypointLegalname')]
    public ?string $paypointLegalname;

    /**
     * @var ?string $paypointEntryname Entry name for the Paypoint, if applicable.
     */
    #[JsonProperty('PaypointEntryname')]
    public ?string $paypointEntryname;

    /**
     * @var ?string $externalPaypointId
     */
    #[JsonProperty('externalPaypointID')]
    public ?string $externalPaypointId;

    /**
     * @var ?int $paypointId The unique identifier for the paypoint.
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
     *   dateCreated?: ?string,
     *   dateModified?: ?string,
     *   associatedVendor?: ?VCardGetResponseAssociatedVendor,
     *   associatedCustomer?: ?string,
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
