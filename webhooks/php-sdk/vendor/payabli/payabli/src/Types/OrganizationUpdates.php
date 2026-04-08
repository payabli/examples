<?php

namespace Payabli\Types;

use Payabli\Core\Json\JsonSerializableType;
use Payabli\Core\Json\JsonProperty;

class OrganizationUpdates extends JsonSerializableType
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
     * @param array{
     *   cascade?: ?bool,
     *   isEnabled?: ?bool,
     * } $values
     */
    public function __construct(
        array $values = [],
    ) {
        $this->cascade = $values['cascade'] ?? null;
        $this->isEnabled = $values['isEnabled'] ?? null;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->toJson();
    }
}
