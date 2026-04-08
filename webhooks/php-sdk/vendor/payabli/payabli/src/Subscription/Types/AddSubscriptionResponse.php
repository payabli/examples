<?php

namespace Payabli\Subscription\Types;

use Payabli\Core\Json\JsonSerializableType;
use Payabli\Core\Json\JsonProperty;

/**
 * Success response
 */
class AddSubscriptionResponse extends JsonSerializableType
{
    /**
     * @var ?int $customerId
     */
    #[JsonProperty('customerId')]
    public ?int $customerId;

    /**
     * @var string $responseText
     */
    #[JsonProperty('responseText')]
    public string $responseText;

    /**
     * @var ?bool $isSuccess
     */
    #[JsonProperty('isSuccess')]
    public ?bool $isSuccess;

    /**
     * @var int $responseData The identifier of the newly created subscription.
     */
    #[JsonProperty('responseData')]
    public int $responseData;

    /**
     * @param array{
     *   responseText: string,
     *   responseData: int,
     *   customerId?: ?int,
     *   isSuccess?: ?bool,
     * } $values
     */
    public function __construct(
        array $values,
    ) {
        $this->customerId = $values['customerId'] ?? null;
        $this->responseText = $values['responseText'];
        $this->isSuccess = $values['isSuccess'] ?? null;
        $this->responseData = $values['responseData'];
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->toJson();
    }
}
