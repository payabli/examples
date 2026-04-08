<?php

namespace Payabli\Types;

use Payabli\Core\Json\JsonSerializableType;
use Payabli\Core\Json\JsonProperty;

class OdpSetup extends JsonSerializableType
{
    /**
     * @var ?bool $allowAch Enables or disables ACH payout functionality
     */
    #[JsonProperty('allowAch')]
    public ?bool $allowAch;

    /**
     * @var ?bool $allowChecks Enables or disables check printing payout functionality
     */
    #[JsonProperty('allowChecks')]
    public ?bool $allowChecks;

    /**
     * @var ?bool $allowVCard Enables or disables vCard payout functionality
     */
    #[JsonProperty('allowVCard')]
    public ?bool $allowVCard;

    /**
     * @var ?value-of<OdpSetupProcessingRegion> $processingRegion Region where payment processing occurs
     */
    #[JsonProperty('processing_region')]
    public ?string $processingRegion;

    /**
     * @var ?string $processor Payment processor identifier
     */
    #[JsonProperty('processor')]
    public ?string $processor;

    /**
     * @var ?string $issuerNetworkSettingsId Reference ID for the program enabled for ODP issuance
     */
    #[JsonProperty('issuerNetworkSettingsId')]
    public ?string $issuerNetworkSettingsId;

    /**
     * @param array{
     *   allowAch?: ?bool,
     *   allowChecks?: ?bool,
     *   allowVCard?: ?bool,
     *   processingRegion?: ?value-of<OdpSetupProcessingRegion>,
     *   processor?: ?string,
     *   issuerNetworkSettingsId?: ?string,
     * } $values
     */
    public function __construct(
        array $values = [],
    ) {
        $this->allowAch = $values['allowAch'] ?? null;
        $this->allowChecks = $values['allowChecks'] ?? null;
        $this->allowVCard = $values['allowVCard'] ?? null;
        $this->processingRegion = $values['processingRegion'] ?? null;
        $this->processor = $values['processor'] ?? null;
        $this->issuerNetworkSettingsId = $values['issuerNetworkSettingsId'] ?? null;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->toJson();
    }
}
