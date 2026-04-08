<?php

namespace Payabli\Types;

use Payabli\Core\Json\JsonSerializableType;
use Payabli\Core\Json\JsonProperty;

class ConfigureGooglePaypointApiResponse extends JsonSerializableType
{
    /**
     * @var bool $isSuccess
     */
    #[JsonProperty('isSuccess')]
    public bool $isSuccess;

    /**
     * @var string $pageIdentifier
     */
    #[JsonProperty('pageIdentifier')]
    public string $pageIdentifier;

    /**
     * @var int $responseCode
     */
    #[JsonProperty('responseCode')]
    public int $responseCode;

    /**
     * @var GooglePayPaypointRegistrationData $responseData
     */
    #[JsonProperty('responseData')]
    public GooglePayPaypointRegistrationData $responseData;

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
     *   isSuccess: bool,
     *   pageIdentifier: string,
     *   responseCode: int,
     *   responseData: GooglePayPaypointRegistrationData,
     *   responseText: string,
     *   roomId?: ?int,
     * } $values
     */
    public function __construct(
        array $values,
    ) {
        $this->isSuccess = $values['isSuccess'];
        $this->pageIdentifier = $values['pageIdentifier'];
        $this->responseCode = $values['responseCode'];
        $this->responseData = $values['responseData'];
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
