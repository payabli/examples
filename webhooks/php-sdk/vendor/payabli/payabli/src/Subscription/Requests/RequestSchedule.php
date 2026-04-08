<?php

namespace Payabli\Subscription\Requests;

use Payabli\Core\Json\JsonSerializableType;
use Payabli\Subscription\Types\SubscriptionRequestBody;

class RequestSchedule extends JsonSerializableType
{
    /**
     * @var ?bool $forceCustomerCreation
     */
    public ?bool $forceCustomerCreation;

    /**
     * @var ?string $idempotencyKey
     */
    public ?string $idempotencyKey;

    /**
     * @var SubscriptionRequestBody $body
     */
    public SubscriptionRequestBody $body;

    /**
     * @param array{
     *   body: SubscriptionRequestBody,
     *   forceCustomerCreation?: ?bool,
     *   idempotencyKey?: ?string,
     * } $values
     */
    public function __construct(
        array $values,
    ) {
        $this->forceCustomerCreation = $values['forceCustomerCreation'] ?? null;
        $this->idempotencyKey = $values['idempotencyKey'] ?? null;
        $this->body = $values['body'];
    }
}
