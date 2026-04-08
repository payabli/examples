<?php

namespace Payabli\Types;

use Payabli\Core\Json\JsonSerializableType;
use Payabli\Core\Json\JsonProperty;

class MfaData extends JsonSerializableType
{
    /**
     * @var ?bool $mfa
     */
    #[JsonProperty('mfa')]
    public ?bool $mfa;

    /**
     * @var ?int $mfaMode
     */
    #[JsonProperty('mfaMode')]
    public ?int $mfaMode;

    /**
     * @param array{
     *   mfa?: ?bool,
     *   mfaMode?: ?int,
     * } $values
     */
    public function __construct(
        array $values = [],
    ) {
        $this->mfa = $values['mfa'] ?? null;
        $this->mfaMode = $values['mfaMode'] ?? null;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->toJson();
    }
}
