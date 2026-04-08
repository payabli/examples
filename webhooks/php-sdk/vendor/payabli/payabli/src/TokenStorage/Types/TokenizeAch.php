<?php

namespace Payabli\TokenStorage\Types;

use Payabli\Core\Json\JsonSerializableType;
use Payabli\Core\Json\JsonProperty;
use Payabli\Types\Achaccounttype;
use Payabli\Types\AchHolderType;

class TokenizeAch extends JsonSerializableType
{
    /**
     * @var string $method The type of payment method to tokenize. For ACH, this is always `ach`.
     */
    #[JsonProperty('method')]
    public string $method;

    /**
     * @var string $achAccount
     */
    #[JsonProperty('achAccount')]
    public string $achAccount;

    /**
     * @var value-of<Achaccounttype> $achAccountType
     */
    #[JsonProperty('achAccountType')]
    public string $achAccountType;

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
     * @var string $achRouting
     */
    #[JsonProperty('achRouting')]
    public string $achRouting;

    /**
     * @var ?string $device
     */
    #[JsonProperty('device')]
    public ?string $device;

    /**
     * @param array{
     *   method: string,
     *   achAccount: string,
     *   achAccountType: value-of<Achaccounttype>,
     *   achHolder: string,
     *   achRouting: string,
     *   achCode?: ?string,
     *   achHolderType?: ?value-of<AchHolderType>,
     *   device?: ?string,
     * } $values
     */
    public function __construct(
        array $values,
    ) {
        $this->method = $values['method'];
        $this->achAccount = $values['achAccount'];
        $this->achAccountType = $values['achAccountType'];
        $this->achCode = $values['achCode'] ?? null;
        $this->achHolder = $values['achHolder'];
        $this->achHolderType = $values['achHolderType'] ?? null;
        $this->achRouting = $values['achRouting'];
        $this->device = $values['device'] ?? null;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->toJson();
    }
}
