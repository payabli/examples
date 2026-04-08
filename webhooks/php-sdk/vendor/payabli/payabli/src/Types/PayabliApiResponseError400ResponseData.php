<?php

namespace Payabli\Types;

use Payabli\Core\Json\JsonSerializableType;
use Payabli\Core\Json\JsonProperty;

/**
 * Describes the reason for a failed operation and how to resolve it.
 */
class PayabliApiResponseError400ResponseData extends JsonSerializableType
{
    /**
     * @var ?string $explanation Describes the reason the operation failed.
     */
    #[JsonProperty('explanation')]
    public ?string $explanation;

    /**
     * @var ?string $todoAction Describes how to resolve the error.
     */
    #[JsonProperty('todoAction')]
    public ?string $todoAction;

    /**
     * @param array{
     *   explanation?: ?string,
     *   todoAction?: ?string,
     * } $values
     */
    public function __construct(
        array $values = [],
    ) {
        $this->explanation = $values['explanation'] ?? null;
        $this->todoAction = $values['todoAction'] ?? null;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->toJson();
    }
}
