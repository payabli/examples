<?php

namespace Payabli\Types;

use Payabli\Core\Json\JsonSerializableType;
use Payabli\Core\Json\JsonProperty;
use Payabli\Core\Types\Union;
use DateTime;
use Payabli\Core\Types\Date;

class QueryTransactionEvents extends JsonSerializableType
{
    /**
     * @var (
     *    array<string, mixed>
     *   |string
     * )|null $eventData Any data associated to the event received from processor. Contents vary by event type.
     */
    #[JsonProperty('EventData'), Union(['string' => 'mixed'], 'string', 'null')]
    public array|string|null $eventData;

    /**
     * @var ?DateTime $eventTime Date and time of event.
     */
    #[JsonProperty('EventTime'), Date(Date::TYPE_DATETIME)]
    public ?DateTime $eventTime;

    /**
     * @var ?string $transEvent Event descriptor. See [TransEvent Reference](/developers/references/transevents) for more details.
     */
    #[JsonProperty('TransEvent')]
    public ?string $transEvent;

    /**
     * @param array{
     *   eventData?: (
     *    array<string, mixed>
     *   |string
     * )|null,
     *   eventTime?: ?DateTime,
     *   transEvent?: ?string,
     * } $values
     */
    public function __construct(
        array $values = [],
    ) {
        $this->eventData = $values['eventData'] ?? null;
        $this->eventTime = $values['eventTime'] ?? null;
        $this->transEvent = $values['transEvent'] ?? null;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->toJson();
    }
}
