<?php

namespace Payabli\Types;

use Payabli\Core\Json\JsonSerializableType;
use Payabli\Core\Json\JsonProperty;
use Payabli\Core\Types\ArrayType;

/**
 * Object containing vendor's bank information
 */
class VendorResponseBillingData extends JsonSerializableType
{
    /**
     * @var ?int $id
     */
    #[JsonProperty('id')]
    public ?int $id;

    /**
     * @var ?string $accountId
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
     * @var ?string $typeAccount
     */
    #[JsonProperty('typeAccount')]
    public ?string $typeAccount;

    /**
     * @var ?string $bankAccountHolderName
     */
    #[JsonProperty('bankAccountHolderName')]
    public ?string $bankAccountHolderName;

    /**
     * @var ?string $bankAccountHolderType
     */
    #[JsonProperty('bankAccountHolderType')]
    public ?string $bankAccountHolderType;

    /**
     * @var ?int $bankAccountFunction
     */
    #[JsonProperty('bankAccountFunction')]
    public ?int $bankAccountFunction;

    /**
     * @var ?bool $verified
     */
    #[JsonProperty('verified')]
    public ?bool $verified;

    /**
     * @var ?int $status
     */
    #[JsonProperty('status')]
    public ?int $status;

    /**
     * @var ?array<mixed> $services
     */
    #[JsonProperty('services'), ArrayType(['mixed'])]
    public ?array $services;

    /**
     * @var ?bool $default
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
