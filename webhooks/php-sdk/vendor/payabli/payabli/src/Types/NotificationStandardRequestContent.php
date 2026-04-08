<?php

namespace Payabli\Types;

use Payabli\Core\Json\JsonSerializableType;
use Payabli\Core\Json\JsonProperty;
use Payabli\Core\Types\ArrayType;

class NotificationStandardRequestContent extends JsonSerializableType
{
    /**
     * @var ?value-of<NotificationStandardRequestContentEventType> $eventType The notification's event name.
     */
    #[JsonProperty('eventType')]
    public ?string $eventType;

    /**
     * @var ?array<KeyValueDuo> $internalData Array of pairs key:value to insert in request body to target in **method** = *web*.
     */
    #[JsonProperty('internalData'), ArrayType([KeyValueDuo::class])]
    public ?array $internalData;

    /**
     * @var ?string $transactionId Used internally to reference the entity or object generating the event.
     */
    #[JsonProperty('transactionId')]
    public ?string $transactionId;

    /**
     * @var ?array<KeyValueDuo> $webHeaderParameters Array of pairs key:value to insert in header of request to target in **method** = *web*.
     */
    #[JsonProperty('webHeaderParameters'), ArrayType([KeyValueDuo::class])]
    public ?array $webHeaderParameters;

    /**
     * @param array{
     *   eventType?: ?value-of<NotificationStandardRequestContentEventType>,
     *   internalData?: ?array<KeyValueDuo>,
     *   transactionId?: ?string,
     *   webHeaderParameters?: ?array<KeyValueDuo>,
     * } $values
     */
    public function __construct(
        array $values = [],
    ) {
        $this->eventType = $values['eventType'] ?? null;
        $this->internalData = $values['internalData'] ?? null;
        $this->transactionId = $values['transactionId'] ?? null;
        $this->webHeaderParameters = $values['webHeaderParameters'] ?? null;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->toJson();
    }
}
