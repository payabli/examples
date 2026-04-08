<?php

namespace Payabli\Traits;

use Payabli\Types\TypeAccount;
use Payabli\Types\BankAccountHolderType;
use Payabli\Core\Json\JsonProperty;
use Payabli\Core\Types\ArrayType;

/**
 * Object that contains bank account details.
 *
 * @property ?int $id
 * @property ?string $accountId
 * @property ?string $nickname
 * @property ?string $bankName
 * @property ?string $routingAccount
 * @property ?string $accountNumber
 * @property ?value-of<TypeAccount> $typeAccount
 * @property ?string $bankAccountHolderName
 * @property ?value-of<BankAccountHolderType> $bankAccountHolderType
 * @property ?int $bankAccountFunction
 * @property ?bool $verified
 * @property ?int $status
 * @property ?array<string> $services
 */
trait Bank
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
}
