<?php

namespace Payabli\Types;

use Payabli\Core\Json\JsonSerializableType;
use Payabli\Core\Json\JsonProperty;

class ScheduleDetail extends JsonSerializableType
{
    /**
     * @var ?string $endDate Subscription end date in any of the accepted formats: YYYY-MM-DD, MM/DD/YYYY or the value `untilcancelled` to indicate a scheduled payment with infinite cycle.
     */
    #[JsonProperty('endDate')]
    public ?string $endDate;

    /**
     * @var ?value-of<Frequency> $frequency Frequency of the subscription.
     */
    #[JsonProperty('frequency')]
    public ?string $frequency;

    /**
     * @var ?int $planId This field is for future development, leave null. Identifier of subscription plan applied in the scheduled payment/subscription.
     */
    #[JsonProperty('planId')]
    public ?int $planId;

    /**
     * @var ?string $startDate Subscription start date in any of the accepted formats: YYYY-MM-DD, MM/DD/YYYY. This must be a future date.
     */
    #[JsonProperty('startDate')]
    public ?string $startDate;

    /**
     * @param array{
     *   endDate?: ?string,
     *   frequency?: ?value-of<Frequency>,
     *   planId?: ?int,
     *   startDate?: ?string,
     * } $values
     */
    public function __construct(
        array $values = [],
    ) {
        $this->endDate = $values['endDate'] ?? null;
        $this->frequency = $values['frequency'] ?? null;
        $this->planId = $values['planId'] ?? null;
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
