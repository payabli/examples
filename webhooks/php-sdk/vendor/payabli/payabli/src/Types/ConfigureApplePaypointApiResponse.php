<?php

namespace Payabli\Types;

use Payabli\Core\Json\JsonSerializableType;
use Payabli\Core\Json\JsonProperty;

class ConfigureApplePaypointApiResponse extends JsonSerializableType
{
    /**
     * @var ?bool $isSuccess
     */
    #[JsonProperty('isSuccess')]
    public ?bool $isSuccess;

    /**
     * @var ?string $pageIdentifier
     */
    #[JsonProperty('pageIdentifier')]
    public ?string $pageIdentifier;

    /**
     * @var ?int $responseCode
     */
    #[JsonProperty('responseCode')]
    public ?int $responseCode;

    /**
     * @var ?ApplePayPaypointRegistrationData $responseData
     */
    #[JsonProperty('responseData')]
    public ?ApplePayPaypointRegistrationData $responseData;

    /**
     * @var string $responseText
     */
    #[JsonProperty('responseText')]
    public string $responseText;

    /**
     * @var ?int $roomId Field not in use on this endpoint
     */
    #[JsonProperty('roomId')]
    public ?int $roomId;

    /**
     * @param array{
     *   responseText: string,
     *   isSuccess?: ?bool,
     *   pageIdentifier?: ?string,
     *   responseCode?: ?int,
     *   responseData?: ?ApplePayPaypointRegistrationData,
     *   roomId?: ?int,
     * } $values
     */
    public function __construct(
        array $values,
    ) {
        $this->isSuccess = $values['isSuccess'] ?? null;
        $this->pageIdentifier = $values['pageIdentifier'] ?? null;
        $this->responseCode = $values['responseCode'] ?? null;
        $this->responseData = $values['responseData'] ?? null;
        $this->responseText = $values['responseText'];
        $this->roomId = $values['roomId'] ?? null;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->toJson();
    }
}
