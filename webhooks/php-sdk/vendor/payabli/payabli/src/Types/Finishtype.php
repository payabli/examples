<?php

namespace Payabli\Types;

use Payabli\Core\Json\JsonSerializableType;
use Payabli\Core\Json\JsonProperty;

class Finishtype extends JsonSerializableType
{
    /**
     * @var ?bool $calendar Flag to enable 'calendar' option
     */
    #[JsonProperty('calendar')]
    public ?bool $calendar;

    /**
     * @var ?bool $untilCancelled Flag to enable 'untilCancelled' option
     */
    #[JsonProperty('untilCancelled')]
    public ?bool $untilCancelled;

    /**
     * @param array{
     *   calendar?: ?bool,
     *   untilCancelled?: ?bool,
     * } $values
     */
    public function __construct(
        array $values = [],
    ) {
        $this->calendar = $values['calendar'] ?? null;
        $this->untilCancelled = $values['untilCancelled'] ?? null;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->toJson();
    }
}
