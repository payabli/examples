<?php

namespace Payabli\Wallet\Requests;

use Payabli\Core\Json\JsonSerializableType;
use Payabli\Core\Json\JsonProperty;

class ConfigurePaypointRequestGooglePay extends JsonSerializableType
{
    /**
     * @var ?string $entry
     */
    #[JsonProperty('entry')]
    public ?string $entry;

    /**
     * @var ?bool $isEnabled When `true`, Google Pay is enabled.
     */
    #[JsonProperty('isEnabled')]
    public ?bool $isEnabled;

    /**
     * @param array{
     *   entry?: ?string,
     *   isEnabled?: ?bool,
     * } $values
     */
    public function __construct(
        array $values = [],
    ) {
        $this->entry = $values['entry'] ?? null;
        $this->isEnabled = $values['isEnabled'] ?? null;
    }
}
