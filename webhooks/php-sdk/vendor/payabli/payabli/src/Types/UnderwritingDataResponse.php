<?php

namespace Payabli\Types;

use Payabli\Core\Json\JsonSerializableType;
use Payabli\Core\Json\JsonProperty;

/**
 * Underwriting data is used to manage risk orchestration in the boarding application lifecycle.
 */
class UnderwritingDataResponse extends JsonSerializableType
{
    /**
     * @var ?value-of<UnderWritingMethod> $method
     */
    #[JsonProperty('method')]
    public ?string $method;

    /**
     * @var ?string $policyId
     */
    #[JsonProperty('policyId')]
    public ?string $policyId;

    /**
     * @param array{
     *   method?: ?value-of<UnderWritingMethod>,
     *   policyId?: ?string,
     * } $values
     */
    public function __construct(
        array $values = [],
    ) {
        $this->method = $values['method'] ?? null;
        $this->policyId = $values['policyId'] ?? null;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->toJson();
    }
}
