<?php

namespace Payabli\Wallet\Requests;

use Payabli\Core\Json\JsonSerializableType;
use Payabli\Core\Json\JsonProperty;

class ConfigureOrganizationRequestGooglePay extends JsonSerializableType
{
    /**
     * @var ?bool $cascade
     */
    #[JsonProperty('cascade')]
    public ?bool $cascade;

    /**
     * @var ?bool $isEnabled
     */
    #[JsonProperty('isEnabled')]
    public ?bool $isEnabled;

    /**
     * @var ?int $orgId
     */
    #[JsonProperty('orgId')]
    public ?int $orgId;

    /**
     * @param array{
     *   cascade?: ?bool,
     *   isEnabled?: ?bool,
     *   orgId?: ?int,
     * } $values
     */
    public function __construct(
        array $values = [],
    ) {
        $this->cascade = $values['cascade'] ?? null;
        $this->isEnabled = $values['isEnabled'] ?? null;
        $this->orgId = $values['orgId'] ?? null;
    }
}
