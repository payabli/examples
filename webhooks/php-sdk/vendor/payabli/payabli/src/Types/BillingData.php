<?php

namespace Payabli\Types;

use Payabli\Core\Json\JsonSerializableType;
use Payabli\Core\Json\JsonProperty;

class BillingData extends JsonSerializableType
{
    /**
     * @var ?string $accountNumber Account number for bank account.
     */
    #[JsonProperty('accountNumber')]
    public ?string $accountNumber;

    /**
     * Describes whether the bank account is used for deposits or withdrawals in Payabli:
     *   - `0`: Deposit
     *   - `1`: Withdrawal
     *   - `2`: Deposit and withdrawal
     *
     * @var ?int $bankAccountFunction
     */
    #[JsonProperty('bankAccountFunction')]
    public ?int $bankAccountFunction;

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
     * @var ?string $bankName
     */
    #[JsonProperty('bankName')]
    public ?string $bankName;

    /**
     * @var ?int $id The bank's ID in Payabli.
     */
    #[JsonProperty('id')]
    public ?int $id;

    /**
     * @var ?string $routingAccount
     */
    #[JsonProperty('routingAccount')]
    public ?string $routingAccount;

    /**
     * @var ?value-of<TypeAccount> $typeAccount
     */
    #[JsonProperty('typeAccount')]
    public ?string $typeAccount;

    /**
     * @param array{
     *   accountNumber?: ?string,
     *   bankAccountFunction?: ?int,
     *   bankAccountHolderName?: ?string,
     *   bankAccountHolderType?: ?value-of<BankAccountHolderType>,
     *   bankName?: ?string,
     *   id?: ?int,
     *   routingAccount?: ?string,
     *   typeAccount?: ?value-of<TypeAccount>,
     * } $values
     */
    public function __construct(
        array $values = [],
    ) {
        $this->accountNumber = $values['accountNumber'] ?? null;
        $this->bankAccountFunction = $values['bankAccountFunction'] ?? null;
        $this->bankAccountHolderName = $values['bankAccountHolderName'] ?? null;
        $this->bankAccountHolderType = $values['bankAccountHolderType'] ?? null;
        $this->bankName = $values['bankName'] ?? null;
        $this->id = $values['id'] ?? null;
        $this->routingAccount = $values['routingAccount'] ?? null;
        $this->typeAccount = $values['typeAccount'] ?? null;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->toJson();
    }
}
