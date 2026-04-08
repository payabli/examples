<?php

namespace Payabli\Types;

use Payabli\Core\Json\JsonSerializableType;
use Payabli\Core\Json\JsonProperty;

class PayabliApiResponseMfaBasic extends JsonSerializableType
{
    /**
     * @var ?bool $isSuccess
     */
    #[JsonProperty('isSuccess')]
    public ?bool $isSuccess;

    /**
     * @var ?bool $mfa
     */
    #[JsonProperty('mfa')]
    public ?bool $mfa;

    /**
     * @var ?string $mfaMode The mode of multi-factor authentication used.
     */
    #[JsonProperty('mfaMode')]
    public ?string $mfaMode;

    /**
     * @var ?string $mfaValidationCode
     */
    #[JsonProperty('mfaValidationCode')]
    public ?string $mfaValidationCode;

    /**
     * @var ?string $responseData Data returned by the response, masked for security.
     */
    #[JsonProperty('responseData')]
    public ?string $responseData;

    /**
     * @var string $responseText
     */
    #[JsonProperty('responseText')]
    public string $responseText;

    /**
     * @param array{
     *   responseText: string,
     *   isSuccess?: ?bool,
     *   mfa?: ?bool,
     *   mfaMode?: ?string,
     *   mfaValidationCode?: ?string,
     *   responseData?: ?string,
     * } $values
     */
    public function __construct(
        array $values,
    ) {
        $this->isSuccess = $values['isSuccess'] ?? null;
        $this->mfa = $values['mfa'] ?? null;
        $this->mfaMode = $values['mfaMode'] ?? null;
        $this->mfaValidationCode = $values['mfaValidationCode'] ?? null;
        $this->responseData = $values['responseData'] ?? null;
        $this->responseText = $values['responseText'];
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->toJson();
    }
}
