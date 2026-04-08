<?php

namespace Payabli\Types;

use Payabli\Core\Json\JsonSerializableType;
use Payabli\Core\Json\JsonProperty;

class UsrAccess extends JsonSerializableType
{
    /**
     * @var ?string $roleLabel
     */
    #[JsonProperty('roleLabel')]
    public ?string $roleLabel;

    /**
     * @var ?bool $roleValue
     */
    #[JsonProperty('roleValue')]
    public ?bool $roleValue;

    /**
     * @param array{
     *   roleLabel?: ?string,
     *   roleValue?: ?bool,
     * } $values
     */
    public function __construct(
        array $values = [],
    ) {
        $this->roleLabel = $values['roleLabel'] ?? null;
        $this->roleValue = $values['roleValue'] ?? null;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->toJson();
    }
}
