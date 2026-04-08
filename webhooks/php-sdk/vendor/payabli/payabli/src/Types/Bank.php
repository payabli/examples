<?php

namespace Payabli\Types;

use Payabli\Core\Json\JsonSerializableType;
use Payabli\Core\Json\JsonProperty;
use Payabli\Core\Types\ArrayType;

/**
 * Object that contains bank account details.
 */
class Bank extends JsonSerializableType
{
    /**
     * @var ?int $id The Payabli-assigned internal identifier for the bank account.
     */
    #[JsonProperty('id')]
    public ?int $id;

    /**
     * @var ?string $accountId A user-defined internal identifier for the bank account. This allows you to specify which bank account should be used for payments in cases where multiple accounts are configured.
     */
    #[JsonProperty('accountId')]
    public ?string $accountId;

    /**
     * @var ?string $nickname
     */
    #[JsonProperty('nickname')]
    public ?string $nickname;

    /**
     * @var ?string $bankName
     */
    #[JsonProperty('bankName')]
    public ?string $bankName;

    /**
     * @var ?string $routingAccount
     */
    #[JsonProperty('routingAccount')]
    public ?string $routingAccount;

    /**
     * @var ?string $accountNumber
     */
    #[JsonProperty('accountNumber')]
    public ?string $accountNumber;

    /**
     * @var ?value-of<TypeAccount> $typeAccount
     */
    #[JsonProperty('typeAccount')]
    public ?string $typeAccount;

    /**
     * @var ?string $bankAccountHolderName
     */
    #[JsonProperty('bankAccountHolderName')]
    public ?string $bankAccountHolderName;

    /**
     * @var ?value-of<BankAccountHolderType> $bankAccountHolderType
     */
    #[JsonProperty('bankAccountHolderType')]
    public ?string $bankAccountHolderType;

    /**
     * @var ?int $bankAccountFunction
     */
    #[JsonProperty('bankAccountFunction')]
    public ?int $bankAccountFunction;

    /**
     * @var ?bool $verified Bank account verification status. When `true`, the account has been verified to exist and be in good standing based on vendor checks or previous processing histories.
     */
    #[JsonProperty('verified')]
    public ?bool $verified;

    /**
     * @var ?int $status Bank account status
     */
    #[JsonProperty('status')]
    public ?int $status;

    /**
     * @var ?array<string> $services Array of services associated with this bank account
     */
    #[JsonProperty('services'), ArrayType(['string'])]
    public ?array $services;

    /**
     * @param array{
     *   id?: ?int,
     *   accountId?: ?string,
     *   nickname?: ?string,
     *   bankName?: ?string,
     *   routingAccount?: ?string,
     *   accountNumber?: ?string,
     *   typeAccount?: ?value-of<TypeAccount>,
     *   bankAccountHolderName?: ?string,
     *   bankAccountHolderType?: ?value-of<BankAccountHolderType>,
     *   bankAccountFunction?: ?int,
     *   verified?: ?bool,
     *   status?: ?int,
     *   services?: ?array<string>,
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
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->toJson();
    }
}
