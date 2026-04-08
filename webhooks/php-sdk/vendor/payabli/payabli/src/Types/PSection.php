<?php

namespace Payabli\Types;

use Payabli\Core\Json\JsonSerializableType;
use Payabli\Core\Json\JsonProperty;

class PSection extends JsonSerializableType
{
    /**
     * @var ?LinkData $avgmonthly
     */
    #[JsonProperty('avgmonthly')]
    public ?LinkData $avgmonthly;

    /**
     * @var ?LinkData $binperson
     */
    #[JsonProperty('binperson')]
    public ?LinkData $binperson;

    /**
     * @var ?LinkData $binphone
     */
    #[JsonProperty('binphone')]
    public ?LinkData $binphone;

    /**
     * @var ?LinkData $binweb
     */
    #[JsonProperty('binweb')]
    public ?LinkData $binweb;

    /**
     * @var ?LinkData $bsummary
     */
    #[JsonProperty('bsummary')]
    public ?LinkData $bsummary;

    /**
     * @var ?LinkData $highticketamt
     */
    #[JsonProperty('highticketamt')]
    public ?LinkData $highticketamt;

    /**
     * @var ?LinkData $mcc
     */
    #[JsonProperty('mcc')]
    public ?LinkData $mcc;

    /**
     * @var ?LinkData $ticketamt
     */
    #[JsonProperty('ticketamt')]
    public ?LinkData $ticketamt;

    /**
     * @var ?LinkData $whenCharged
     */
    #[JsonProperty('whenCharged')]
    public ?LinkData $whenCharged;

    /**
     * @var ?LinkData $whenDelivered
     */
    #[JsonProperty('whenDelivered')]
    public ?LinkData $whenDelivered;

    /**
     * @var ?LinkData $whenProvided
     */
    #[JsonProperty('whenProvided')]
    public ?LinkData $whenProvided;

    /**
     * @var ?LinkData $whenRefunded
     */
    #[JsonProperty('whenRefunded')]
    public ?LinkData $whenRefunded;

    /**
     * @param array{
     *   avgmonthly?: ?LinkData,
     *   binperson?: ?LinkData,
     *   binphone?: ?LinkData,
     *   binweb?: ?LinkData,
     *   bsummary?: ?LinkData,
     *   highticketamt?: ?LinkData,
     *   mcc?: ?LinkData,
     *   ticketamt?: ?LinkData,
     *   whenCharged?: ?LinkData,
     *   whenDelivered?: ?LinkData,
     *   whenProvided?: ?LinkData,
     *   whenRefunded?: ?LinkData,
     * } $values
     */
    public function __construct(
        array $values = [],
    ) {
        $this->avgmonthly = $values['avgmonthly'] ?? null;
        $this->binperson = $values['binperson'] ?? null;
        $this->binphone = $values['binphone'] ?? null;
        $this->binweb = $values['binweb'] ?? null;
        $this->bsummary = $values['bsummary'] ?? null;
        $this->highticketamt = $values['highticketamt'] ?? null;
        $this->mcc = $values['mcc'] ?? null;
        $this->ticketamt = $values['ticketamt'] ?? null;
        $this->whenCharged = $values['whenCharged'] ?? null;
        $this->whenDelivered = $values['whenDelivered'] ?? null;
        $this->whenProvided = $values['whenProvided'] ?? null;
        $this->whenRefunded = $values['whenRefunded'] ?? null;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->toJson();
    }
}
