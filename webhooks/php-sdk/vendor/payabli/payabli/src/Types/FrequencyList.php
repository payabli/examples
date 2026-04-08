<?php

namespace Payabli\Types;

use Payabli\Core\Json\JsonSerializableType;
use Payabli\Core\Json\JsonProperty;

class FrequencyList extends JsonSerializableType
{
    /**
     * @var ?bool $annually Enable or disable frequency
     */
    #[JsonProperty('annually')]
    public ?bool $annually;

    /**
     * @var ?bool $every2Weeks Enable or disable frequency
     */
    #[JsonProperty('every2Weeks')]
    public ?bool $every2Weeks;

    /**
     * @var ?bool $every3Months Enable or disable frequency
     */
    #[JsonProperty('every3Months')]
    public ?bool $every3Months;

    /**
     * @var ?bool $every6Months Enable or disable frequency
     */
    #[JsonProperty('every6Months')]
    public ?bool $every6Months;

    /**
     * @var ?bool $monthly Enable or disable frequency
     */
    #[JsonProperty('monthly')]
    public ?bool $monthly;

    /**
     * @var ?bool $oneTime Enable or disable frequency
     */
    #[JsonProperty('one-time')]
    public ?bool $oneTime;

    /**
     * @var ?bool $weekly Enable or disable frequency
     */
    #[JsonProperty('weekly')]
    public ?bool $weekly;

    /**
     * @param array{
     *   annually?: ?bool,
     *   every2Weeks?: ?bool,
     *   every3Months?: ?bool,
     *   every6Months?: ?bool,
     *   monthly?: ?bool,
     *   oneTime?: ?bool,
     *   weekly?: ?bool,
     * } $values
     */
    public function __construct(
        array $values = [],
    ) {
        $this->annually = $values['annually'] ?? null;
        $this->every2Weeks = $values['every2Weeks'] ?? null;
        $this->every3Months = $values['every3Months'] ?? null;
        $this->every6Months = $values['every6Months'] ?? null;
        $this->monthly = $values['monthly'] ?? null;
        $this->oneTime = $values['oneTime'] ?? null;
        $this->weekly = $values['weekly'] ?? null;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->toJson();
    }
}
