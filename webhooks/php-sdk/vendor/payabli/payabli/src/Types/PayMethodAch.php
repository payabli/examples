<?php

namespace Payabli\Types;

use Payabli\Core\Json\JsonSerializableType;
use Payabli\Core\Json\JsonProperty;

class PayMethodAch extends JsonSerializableType
{
    /**
     * @var string $achAccount Bank account number. This field is **required** when method = 'ach'.
     */
    #[JsonProperty('achAccount')]
    public string $achAccount;

    /**
     * @var ?value-of<Achaccounttype> $achAccountType Bank account type. This field is **required** when method = 'ach'.
     */
    #[JsonProperty('achAccountType')]
    public ?string $achAccountType;

    /**
     * @var ?string $achCode
     */
    #[JsonProperty('achCode')]
    public ?string $achCode;

    /**
     * @var string $achHolder
     */
    #[JsonProperty('achHolder')]
    public string $achHolder;

    /**
     * @var ?value-of<AchHolderType> $achHolderType
     */
    #[JsonProperty('achHolderType')]
    public ?string $achHolderType;

    /**
     * @var string $achRouting ABA/routing number of bank account. This field is **required** when method = 'ach'.
     */
    #[JsonProperty('achRouting')]
    public string $achRouting;

    /**
     * @var ?string $device
     */
    #[JsonProperty('device')]
    public ?string $device;

    /**
     * @var 'ach' $method
     */
    #[JsonProperty('method')]
    public string $method;

    /**
     * @param array{
     *   achAccount: string,
     *   achHolder: string,
     *   achRouting: string,
     *   method: 'ach',
     *   achAccountType?: ?value-of<Achaccounttype>,
     *   achCode?: ?string,
     *   achHolderType?: ?value-of<AchHolderType>,
     *   device?: ?string,
     * } $values
     */
    public function __construct(
        array $values,
    ) {
        $this->achAccount = $values['achAccount'];
        $this->achAccountType = $values['achAccountType'] ?? null;
        $this->achCode = $values['achCode'] ?? null;
        $this->achHolder = $values['achHolder'];
        $this->achHolderType = $values['achHolderType'] ?? null;
        $this->achRouting = $values['achRouting'];
        $this->device = $values['device'] ?? null;
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
