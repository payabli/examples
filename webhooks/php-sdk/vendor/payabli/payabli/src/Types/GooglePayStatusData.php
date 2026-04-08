<?php

namespace Payabli\Types;

use Payabli\Core\Json\JsonSerializableType;
use Payabli\Core\Json\JsonProperty;

/**
 * Details about the Google Pay service status.
 */
class GooglePayStatusData extends JsonSerializableType
{
    /**
     * @var ?string $errorMessage Any error message related to Google Pay's activation status.
     */
    #[JsonProperty('errorMessage')]
    public ?string $errorMessage;

    /**
     * @var ?GooglePayMetadata $metadata
     */
    #[JsonProperty('metadata')]
    public ?GooglePayMetadata $metadata;

    /**
     * @param array{
     *   errorMessage?: ?string,
     *   metadata?: ?GooglePayMetadata,
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
