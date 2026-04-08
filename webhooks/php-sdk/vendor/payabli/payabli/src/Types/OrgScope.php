<?php

namespace Payabli\Types;

use Payabli\Core\Json\JsonSerializableType;
use Payabli\Core\Json\JsonProperty;

class OrgScope extends JsonSerializableType
{
    /**
     * @var ?int $orgId
     */
    #[JsonProperty('orgId')]
    public ?int $orgId;

    /**
     * @var ?int $orgType
     */
    #[JsonProperty('orgType')]
    public ?int $orgType;

    /**
     * @param array{
     *   orgId?: ?int,
     *   orgType?: ?int,
     * } $values
     */
    public function __construct(
        array $values = [],
    ) {
        $this->orgId = $values['orgId'] ?? null;
        $this->orgType = $values['orgType'] ?? null;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->toJson();
    }
}
