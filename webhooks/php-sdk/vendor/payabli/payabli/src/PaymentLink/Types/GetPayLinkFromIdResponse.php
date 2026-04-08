<?php

namespace Payabli\PaymentLink\Types;

use Payabli\Core\Json\JsonSerializableType;
use Payabli\Traits\PayabliApiResponseGeneric2Part;
use Payabli\Core\Json\JsonProperty;

class GetPayLinkFromIdResponse extends JsonSerializableType
{
    use PayabliApiResponseGeneric2Part;

    /**
     * @var ?GetPayLinkFromIdResponseResponseData $responseData
     */
    #[JsonProperty('responseData')]
    public ?GetPayLinkFromIdResponseResponseData $responseData;

    /**
     * @param array{
     *   responseText: string,
     *   isSuccess?: ?bool,
     *   responseData?: ?GetPayLinkFromIdResponseResponseData,
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
