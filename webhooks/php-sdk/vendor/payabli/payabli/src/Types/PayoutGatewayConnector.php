<?php

namespace Payabli\Types;

use Payabli\Core\Json\JsonSerializableType;
use Payabli\Core\Json\JsonProperty;

class PayoutGatewayConnector extends JsonSerializableType
{
    /**
     * @var ?string $configuration
     */
    #[JsonProperty('configuration')]
    public ?string $configuration;

    /**
     * @var ?string $name
     */
    #[JsonProperty('Name')]
    public ?string $name;

    /**
     * @var ?int $mode
     */
    #[JsonProperty('Mode')]
    public ?int $mode;

    /**
     * @var ?string $bank
     */
    #[JsonProperty('Bank')]
    public ?string $bank;

    /**
     * @var ?string $descriptor
     */
    #[JsonProperty('Descriptor')]
    public ?string $descriptor;

    /**
     * @var ?int $gatewayId
     */
    #[JsonProperty('gatewayID')]
    public ?int $gatewayId;

    /**
     * @var ?bool $enabled
     */
    #[JsonProperty('Enabled')]
    public ?bool $enabled;

    /**
     * @var ?bool $enableAchValidation
     */
    #[JsonProperty('EnableACHValidation')]
    public ?bool $enableAchValidation;

    /**
     * @var ?bool $testMode
     */
    #[JsonProperty('TestMode')]
    public ?bool $testMode;

    /**
     * @param array{
     *   configuration?: ?string,
     *   name?: ?string,
     *   mode?: ?int,
     *   bank?: ?string,
     *   descriptor?: ?string,
     *   gatewayId?: ?int,
     *   enabled?: ?bool,
     *   enableAchValidation?: ?bool,
     *   testMode?: ?bool,
     * } $values
     */
    public function __construct(
        array $values = [],
    ) {
        $this->configuration = $values['configuration'] ?? null;
        $this->name = $values['name'] ?? null;
        $this->mode = $values['mode'] ?? null;
        $this->bank = $values['bank'] ?? null;
        $this->descriptor = $values['descriptor'] ?? null;
        $this->gatewayId = $values['gatewayId'] ?? null;
        $this->enabled = $values['enabled'] ?? null;
        $this->enableAchValidation = $values['enableAchValidation'] ?? null;
        $this->testMode = $values['testMode'] ?? null;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->toJson();
    }
}
