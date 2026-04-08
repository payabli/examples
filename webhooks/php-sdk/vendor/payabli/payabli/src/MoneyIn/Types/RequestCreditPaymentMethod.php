<?php

namespace Payabli\MoneyIn\Types;

use Payabli\Core\Json\JsonSerializableType;
use Payabli\Core\Json\JsonProperty;
use Payabli\Types\Achaccounttype;

/**
 * Object describing the ACH payment method to use for transaction.
 */
class RequestCreditPaymentMethod extends JsonSerializableType
{
    /**
     * @var ?string $achAccount
     */
    #[JsonProperty('achAccount')]
    public ?string $achAccount;

    /**
     * @var ?value-of<Achaccounttype> $achAccountType
     */
    #[JsonProperty('achAccountType')]
    public ?string $achAccountType;

    /**
     * @var ?string $achCode
     */
    #[JsonProperty('achCode')]
    public ?string $achCode;

    /**
     * @var ?string $achHolder Bank account holder.
     */
    #[JsonProperty('achHolder')]
    public ?string $achHolder;

    /**
     * @var ?string $achRouting
     */
    #[JsonProperty('achRouting')]
    public ?string $achRouting;

    /**
     * @var 'ach' $method Method to use for the transaction. Must be ACH.
     */
    #[JsonProperty('method')]
    public string $method;

    /**
     * @param array{
     *   method: 'ach',
     *   achAccount?: ?string,
     *   achAccountType?: ?value-of<Achaccounttype>,
     *   achCode?: ?string,
     *   achHolder?: ?string,
     *   achRouting?: ?string,
     * } $values
     */
    public function __construct(
        array $values,
    ) {
        $this->achAccount = $values['achAccount'] ?? null;
        $this->achAccountType = $values['achAccountType'] ?? null;
        $this->achCode = $values['achCode'] ?? null;
        $this->achHolder = $values['achHolder'] ?? null;
        $this->achRouting = $values['achRouting'] ?? null;
        $this->method = $values['method'];
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->toJson();
    }
}
