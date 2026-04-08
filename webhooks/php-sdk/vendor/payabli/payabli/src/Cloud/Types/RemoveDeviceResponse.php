<?php

namespace Payabli\Cloud\Types;

use Payabli\Core\Json\JsonSerializableType;
use Payabli\Traits\PayabliApiResponseGeneric2Part;
use Payabli\Core\Json\JsonProperty;

class RemoveDeviceResponse extends JsonSerializableType
{
    use PayabliApiResponseGeneric2Part;

    /**
     * @var ?string $pageIdentifier
     */
    #[JsonProperty('pageIdentifier')]
    public ?string $pageIdentifier;

    /**
     * If `isSuccess` = true, this contains the device identifier.
     * If `isSuccess` = false, this contains the reason for the error.
     *
     * @var ?string $responseData
     */
    #[JsonProperty('responseData')]
    public ?string $responseData;

    /**
     * @param array{
     *   responseText: string,
     *   isSuccess?: ?bool,
     *   pageIdentifier?: ?string,
     *   responseData?: ?string,
     * } $values
     */
    public function __construct(
        array $values,
    ) {
        $this->isSuccess = $values['isSuccess'] ?? null;
        $this->responseText = $values['responseText'];
        $this->pageIdentifier = $values['pageIdentifier'] ?? null;
        $this->responseData = $values['responseData'] ?? null;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->toJson();
    }
}
