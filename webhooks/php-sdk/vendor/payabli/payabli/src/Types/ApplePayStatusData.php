<?php

namespace Payabli\Types;

use Payabli\Core\Json\JsonSerializableType;
use Payabli\Core\Json\JsonProperty;

/**
 * Details about the Apple Pay service status.
 */
class ApplePayStatusData extends JsonSerializableType
{
    /**
     * @var ?string $errorMessage Any error message related to Apple Pay's activation status.
     */
    #[JsonProperty('errorMessage')]
    public ?string $errorMessage;

    /**
     * @var ?ApplePayMetadata $metadata
     */
    #[JsonProperty('metadata')]
    public ?ApplePayMetadata $metadata;

    /**
     * @param array{
     *   errorMessage?: ?string,
     *   metadata?: ?ApplePayMetadata,
     * } $values
     */
    public function __construct(
        array $values = [],
    ) {
        $this->errorMessage = $values['errorMessage'] ?? null;
        $this->metadata = $values['metadata'] ?? null;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->toJson();
    }
}
