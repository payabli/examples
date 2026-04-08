<?php

namespace Payabli\MoneyOutTypes\Types;

use Payabli\Core\Json\JsonSerializableType;
use Payabli\Core\Json\JsonProperty;
use Payabli\Types\TypeAccount;

/**
 * Object containing vendor's bank information. This object is deprecated for this endpoint. Use the `paymentMethod` object in payout authorize requests instead.
 */
class RequestOutAuthorizeVendorBillingData extends JsonSerializableType
{
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
     * @param array{
     *   bankName?: ?string,
     *   routingAccount?: ?string,
     *   accountNumber?: ?string,
     *   typeAccount?: ?value-of<TypeAccount>,
     *   bankAccountHolderName?: ?string,
     * } $values
     */
    public function __construct(
        array $values = [],
    ) {
        $this->bankName = $values['bankName'] ?? null;
        $this->routingAccount = $values['routingAccount'] ?? null;
        $this->accountNumber = $values['accountNumber'] ?? null;
        $this->typeAccount = $values['typeAccount'] ?? null;
        $this->bankAccountHolderName = $values['bankAccountHolderName'] ?? null;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->toJson();
    }
}
