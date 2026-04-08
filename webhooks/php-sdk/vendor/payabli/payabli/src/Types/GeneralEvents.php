<?php

namespace Payabli\Types;

use Payabli\Core\Json\JsonSerializableType;
use Payabli\Core\Json\JsonProperty;
use DateTime;
use Payabli\Core\Types\Date;
use Payabli\Core\Types\ArrayType;

class GeneralEvents extends JsonSerializableType
{
    /**
     * @var ?string $description Event description.
     */
    #[JsonProperty('description')]
    public ?string $description;

    /**
     * @var ?DateTime $eventTime Event timestamp, in UTC.
     */
    #[JsonProperty('eventTime'), Date(Date::TYPE_DATETIME)]
    public ?DateTime $eventTime;

    /**
     * @var ?array<string, mixed> $extraData Extra data.
     */
    #[JsonProperty('extraData'), ArrayType(['string' => 'mixed'])]
    public ?array $extraData;

    /**
     * @var ?string $refData Reference data.
     */
    #[JsonProperty('refData')]
    public ?string $refData;

    /**
     * @var ?string $source The event source.
     */
    #[JsonProperty('source')]
    public ?string $source;

    /**
     * @param array{
     *   description?: ?string,
     *   eventTime?: ?DateTime,
     *   extraData?: ?array<string, mixed>,
     *   refData?: ?string,
     *   source?: ?string,
     * } $values
     */
    public function __construct(
        array $values = [],
    ) {
        $this->description = $values['description'] ?? null;
        $this->eventTime = $values['eventTime'] ?? null;
        $this->extraData = $values['extraData'] ?? null;
        $this->refData = $values['refData'] ?? null;
        $this->source = $values['source'] ?? null;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->toJson();
    }
}
