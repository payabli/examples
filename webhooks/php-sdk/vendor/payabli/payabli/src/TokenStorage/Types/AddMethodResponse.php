<?php

namespace Payabli\TokenStorage\Types;

use Payabli\Core\Json\JsonSerializableType;
use Payabli\Traits\PayabliApiResponseGeneric2Part;
use Payabli\Core\Json\JsonProperty;

class AddMethodResponse extends JsonSerializableType
{
    use PayabliApiResponseGeneric2Part;

    /**
     * @var ?AddMethodResponseResponseData $responseData
     */
    #[JsonProperty('responseData')]
    public ?AddMethodResponseResponseData $responseData;

    /**
     * @param array{
     *   responseText: string,
     *   isSuccess?: ?bool,
     *   responseData?: ?AddMethodResponseResponseData,
     * } $values
     */
    public function __construct(
        array $values,
    ) {
        $this->isSuccess = $values['isSuccess'] ?? null;
        $this->responseText = $values['responseText'];
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
