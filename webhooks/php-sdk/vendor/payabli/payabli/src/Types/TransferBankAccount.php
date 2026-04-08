<?php

namespace Payabli\Types;

use Payabli\Core\Json\JsonSerializableType;
use Payabli\Core\Json\JsonProperty;

class TransferBankAccount extends JsonSerializableType
{
    /**
     * @var string $accountNumber
     */
    #[JsonProperty('accountNumber')]
    public string $accountNumber;

    /**
     * @var string $routingNumber
     */
    #[JsonProperty('routingNumber')]
    public string $routingNumber;

    /**
     * @param array{
     *   accountNumber: string,
     *   routingNumber: string,
     * } $values
     */
    public function __construct(
        array $values,
    ) {
        $this->accountNumber = $values['accountNumber'];
        $this->routingNumber = $values['routingNumber'];
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->toJson();
    }
}
