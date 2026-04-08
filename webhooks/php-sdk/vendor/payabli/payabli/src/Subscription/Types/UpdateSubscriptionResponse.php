<?php

namespace Payabli\Subscription\Types;

use Payabli\Core\Json\JsonSerializableType;
use Payabli\Core\Json\JsonProperty;

/**
 * Success response
 */
class UpdateSubscriptionResponse extends JsonSerializableType
{
    /**
     * @var ?bool $isSuccess
     */
    #[JsonProperty('isSuccess')]
    public ?bool $isSuccess;

    /**
     *
     * If `isSuccess` = true, this contains the identifier of the subscription, and sometimes extra information, depending on what was updated.
     *
     * If `isSuccess` = false, this contains the reason for the failure.
     *
     * @var ?string $responseData
     */
    #[JsonProperty('responseData')]
    public ?string $responseData;

    /**
     * @var string $responseText
     */
    #[JsonProperty('responseText')]
    public string $responseText;

    /**
     * @var ?int $customerId
     */
    #[JsonProperty('customerId')]
    public ?int $customerId;

    /**
     * @param array{
     *   responseText: string,
     *   isSuccess?: ?bool,
     *   responseData?: ?string,
     *   customerId?: ?int,
     * } $values
     */
    public function __construct(
        array $values,
    ) {
        $this->isSuccess = $values['isSuccess'] ?? null;
        $this->responseData = $values['responseData'] ?? null;
        $this->responseText = $values['responseText'];
        $this->customerId = $values['customerId'] ?? null;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->toJson();
    }
}
