<?php

namespace Payabli\Types;

use Payabli\Core\Json\JsonSerializableType;
use Payabli\Core\Json\JsonProperty;

class Bnk extends JsonSerializableType
{
    /**
     * @var ?LinkData $accountNumber
     */
    #[JsonProperty('accountNumber')]
    public ?LinkData $accountNumber;

    /**
     * @var ?LinkData $bankName
     */
    #[JsonProperty('bankName')]
    public ?LinkData $bankName;

    /**
     * @var ?LinkData $routingAccount
     */
    #[JsonProperty('routingAccount')]
    public ?LinkData $routingAccount;

    /**
     * @var ?LinkData $typeAccount
     */
    #[JsonProperty('typeAccount')]
    public ?LinkData $typeAccount;

    /**
     * @param array{
     *   accountNumber?: ?LinkData,
     *   bankName?: ?LinkData,
     *   routingAccount?: ?LinkData,
     *   typeAccount?: ?LinkData,
     * } $values
     */
    public function __construct(
        array $values = [],
    ) {
        $this->accountNumber = $values['accountNumber'] ?? null;
        $this->bankName = $values['bankName'] ?? null;
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
