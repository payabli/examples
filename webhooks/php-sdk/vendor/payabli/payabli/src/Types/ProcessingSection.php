<?php

namespace Payabli\Types;

use Payabli\Core\Json\JsonSerializableType;
use Payabli\Core\Json\JsonProperty;

class ProcessingSection extends JsonSerializableType
{
    /**
     * @var ?TemplateElement $avgmonthly
     */
    #[JsonProperty('avgmonthly')]
    public ?TemplateElement $avgmonthly;

    /**
     * @var ?TemplateElement $binperson
     */
    #[JsonProperty('binperson')]
    public ?TemplateElement $binperson;

    /**
     * @var ?TemplateElement $binphone
     */
    #[JsonProperty('binphone')]
    public ?TemplateElement $binphone;

    /**
     * @var ?TemplateElement $binweb
     */
    #[JsonProperty('binweb')]
    public ?TemplateElement $binweb;

    /**
     * @var ?TemplateElement $bsummary
     */
    #[JsonProperty('bsummary')]
    public ?TemplateElement $bsummary;

    /**
     * @var ?TemplateElement $highticketamt
     */
    #[JsonProperty('highticketamt')]
    public ?TemplateElement $highticketamt;

    /**
     * @var ?TemplateElement $mcc
     */
    #[JsonProperty('mcc')]
    public ?TemplateElement $mcc;

    /**
     * @var ?string $subFooter
     */
    #[JsonProperty('subFooter')]
    public ?string $subFooter;

    /**
     * @var ?string $subHeader
     */
    #[JsonProperty('subHeader')]
    public ?string $subHeader;

    /**
     * @var ?TemplateElement $ticketamt
     */
    #[JsonProperty('ticketamt')]
    public ?TemplateElement $ticketamt;

    /**
     * @var ?bool $visible
     */
    #[JsonProperty('visible')]
    public ?bool $visible;

    /**
     * @var ?TemplateElement $whenCharged
     */
    #[JsonProperty('whenCharged')]
    public ?TemplateElement $whenCharged;

    /**
     * @var ?TemplateElement $whenDelivered
     */
    #[JsonProperty('whenDelivered')]
    public ?TemplateElement $whenDelivered;

    /**
     * @var ?TemplateElement $whenProvided
     */
    #[JsonProperty('whenProvided')]
    public ?TemplateElement $whenProvided;

    /**
     * @var ?TemplateElement $whenRefunded
     */
    #[JsonProperty('whenRefunded')]
    public ?TemplateElement $whenRefunded;

    /**
     * @param array{
     *   avgmonthly?: ?TemplateElement,
     *   binperson?: ?TemplateElement,
     *   binphone?: ?TemplateElement,
     *   binweb?: ?TemplateElement,
     *   bsummary?: ?TemplateElement,
     *   highticketamt?: ?TemplateElement,
     *   mcc?: ?TemplateElement,
     *   subFooter?: ?string,
     *   subHeader?: ?string,
     *   ticketamt?: ?TemplateElement,
     *   visible?: ?bool,
     *   whenCharged?: ?TemplateElement,
     *   whenDelivered?: ?TemplateElement,
     *   whenProvided?: ?TemplateElement,
     *   whenRefunded?: ?TemplateElement,
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
        $this->subFooter = $values['subFooter'] ?? null;
        $this->subHeader = $values['subHeader'] ?? null;
        $this->ticketamt = $values['ticketamt'] ?? null;
        $this->visible = $values['visible'] ?? null;
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
