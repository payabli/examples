<?php

namespace Payabli\Types;

use Payabli\Core\Json\JsonSerializableType;
use Payabli\Core\Json\JsonProperty;

class ApplePayPaypointRegistrationData extends JsonSerializableType
{
    /**
     * @var ?string $entry
     */
    #[JsonProperty('entry')]
    public ?string $entry;

    /**
     * @var ?bool $isEnabled
     */
    #[JsonProperty('isEnabled')]
    public ?bool $isEnabled;

    /**
     * @var ?string $walletType The wallet type. In this context it will always be `applePay`.
     */
    #[JsonProperty('walletType')]
    public ?string $walletType;

    /**
     * @var ?AppleWalletData $walletData
     */
    #[JsonProperty('walletData')]
    public ?AppleWalletData $walletData;

    /**
     * @param array{
     *   entry?: ?string,
     *   isEnabled?: ?bool,
     *   walletType?: ?string,
     *   walletData?: ?AppleWalletData,
     * } $values
     */
    public function __construct(
        array $values = [],
    ) {
        $this->entry = $values['entry'] ?? null;
        $this->isEnabled = $values['isEnabled'] ?? null;
        $this->walletType = $values['walletType'] ?? null;
        $this->walletData = $values['walletData'] ?? null;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->toJson();
    }
}
