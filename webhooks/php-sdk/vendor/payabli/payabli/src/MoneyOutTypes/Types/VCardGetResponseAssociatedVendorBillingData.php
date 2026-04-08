<?php

namespace Payabli\MoneyOutTypes\Types;

use Payabli\Core\Json\JsonSerializableType;
use Payabli\Core\Json\JsonProperty;
use Payabli\Core\Types\ArrayType;

/**
 * Billing data for the vendor.
 */
class VCardGetResponseAssociatedVendorBillingData extends JsonSerializableType
{
    /**
     * @var ?int $id Unique identifier for billing data.
     */
    #[JsonProperty('id')]
    public ?int $id;

    /**
     * @var ?string $accountId Account identifier.
     */
    #[JsonProperty('accountId')]
    public ?string $accountId;

    /**
     * @var ?string $nickname Nickname for the account.
     */
    #[JsonProperty('nickname')]
    public ?string $nickname;

    /**
     * @var ?string $bankName Name of the bank used for transactions.
     */
    #[JsonProperty('bankName')]
    public ?string $bankName;

    /**
     * @var ?string $routingAccount Routing number for the bank account.
     */
    #[JsonProperty('routingAccount')]
    public ?string $routingAccount;

    /**
     * @var ?string $accountNumber Masked account number for transactions.
     */
    #[JsonProperty('accountNumber')]
    public ?string $accountNumber;

    /**
     * @var ?string $typeAccount Type of the bank account.
     */
    #[JsonProperty('typeAccount')]
    public ?string $typeAccount;

    /**
     * @var ?string $bankAccountHolderName Name of the bank account holder.
     */
    #[JsonProperty('bankAccountHolderName')]
    public ?string $bankAccountHolderName;

    /**
     * @var ?string $bankAccountHolderType Type of bank account holder.
     */
    #[JsonProperty('bankAccountHolderType')]
    public ?string $bankAccountHolderType;

    /**
     * @var ?int $bankAccountFunction Function of the bank account.
     */
    #[JsonProperty('bankAccountFunction')]
    public ?int $bankAccountFunction;

    /**
     * @var ?bool $verified Indicates if the account is verified.
     */
    #[JsonProperty('verified')]
    public ?bool $verified;

    /**
     * @var ?int $status Status of the billing data.
     */
    #[JsonProperty('status')]
    public ?int $status;

    /**
     * @var ?array<mixed> $services Services associated with the account.
     */
    #[JsonProperty('services'), ArrayType(['mixed'])]
    public ?array $services;

    /**
     * @var ?bool $default Indicates if this is the default billing account.
     */
    #[JsonProperty('default')]
    public ?bool $default;

    /**
     * @param array{
     *   id?: ?int,
     *   accountId?: ?string,
     *   nickname?: ?string,
     *   bankName?: ?string,
     *   routingAccount?: ?string,
     *   accountNumber?: ?string,
     *   typeAccount?: ?string,
     *   bankAccountHolderName?: ?string,
     *   bankAccountHolderType?: ?string,
     *   bankAccountFunction?: ?int,
     *   verified?: ?bool,
     *   status?: ?int,
     *   services?: ?array<mixed>,
     *   default?: ?bool,
     * } $values
     */
    public function __construct(
        array $values = [],
    ) {
        $this->id = $values['id'] ?? null;
        $this->accountId = $values['accountId'] ?? null;
        $this->nickname = $values['nickname'] ?? null;
        $this->bankName = $values['bankName'] ?? null;
        $this->routingAccount = $values['routingAccount'] ?? null;
        $this->accountNumber = $values['accountNumber'] ?? null;
        $this->typeAccount = $values['typeAccount'] ?? null;
        $this->bankAccountHolderName = $values['bankAccountHolderName'] ?? null;
        $this->bankAccountHolderType = $values['bankAccountHolderType'] ?? null;
        $this->bankAccountFunction = $values['bankAccountFunction'] ?? null;
        $this->verified = $values['verified'] ?? null;
        $this->status = $values['status'] ?? null;
        $this->services = $values['services'] ?? null;
        $this->default = $values['default'] ?? null;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->toJson();
    }
}
