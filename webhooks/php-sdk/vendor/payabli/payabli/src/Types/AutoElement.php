<?php

namespace Payabli\Types;

use Payabli\Core\Json\JsonSerializableType;
use Payabli\Core\Json\JsonProperty;

class AutoElement extends JsonSerializableType
{
    /**
     * @var ?bool $enabled
     */
    #[JsonProperty('enabled')]
    public ?bool $enabled;

    /**
     * @var ?Finishtype $finish Type of end date
     */
    #[JsonProperty('finish')]
    public ?Finishtype $finish;

    /**
     * @var ?FrequencyList $frequency accepted frequencies for autopay
     */
    #[JsonProperty('frequency')]
    public ?FrequencyList $frequency;

    /**
     * @var ?string $frequencySelected Value of pre-selected frequency
     */
    #[JsonProperty('frequencySelected')]
    public ?string $frequencySelected;

    /**
     * @var ?string $header Header text for section
     */
    #[JsonProperty('header')]
    public ?string $header;

    /**
     * @var ?int $order
     */
    #[JsonProperty('order')]
    public ?int $order;

    /**
     * @var ?string $startDate Range of days enabled in calendar. Leave empty to enable all days.
     */
    #[JsonProperty('startDate')]
    public ?string $startDate;

    /**
     * @param array{
     *   enabled?: ?bool,
     *   finish?: ?Finishtype,
     *   frequency?: ?FrequencyList,
     *   frequencySelected?: ?string,
     *   header?: ?string,
     *   order?: ?int,
     *   startDate?: ?string,
     * } $values
     */
    public function __construct(
        array $values = [],
    ) {
        $this->enabled = $values['enabled'] ?? null;
        $this->finish = $values['finish'] ?? null;
        $this->frequency = $values['frequency'] ?? null;
        $this->frequencySelected = $values['frequencySelected'] ?? null;
        $this->header = $values['header'] ?? null;
        $this->order = $values['order'] ?? null;
        $this->startDate = $values['startDate'] ?? null;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->toJson();
    }
}
