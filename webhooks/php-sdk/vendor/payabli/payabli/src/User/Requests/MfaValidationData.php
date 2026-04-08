<?php

namespace Payabli\User\Requests;

use Payabli\Core\Json\JsonSerializableType;
use Payabli\Core\Json\JsonProperty;

class MfaValidationData extends JsonSerializableType
{
    /**
     * @var ?string $mfaCode
     */
    #[JsonProperty('mfaCode')]
    public ?string $mfaCode;

    /**
     * @var ?string $mfaValidationCode
     */
    #[JsonProperty('mfaValidationCode')]
    public ?string $mfaValidationCode;

    /**
     * @param array{
     *   mfaCode?: ?string,
     *   mfaValidationCode?: ?string,
     * } $values
     */
    public function __construct(
        array $values = [],
    ) {
        $this->mfaCode = $values['mfaCode'] ?? null;
        $this->mfaValidationCode = $values['mfaValidationCode'] ?? null;
    }
}
