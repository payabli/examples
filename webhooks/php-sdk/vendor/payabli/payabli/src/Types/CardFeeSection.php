<?php

namespace Payabli\Types;

use Payabli\Core\Json\JsonSerializableType;
use Payabli\Core\Json\JsonProperty;

class CardFeeSection extends JsonSerializableType
{
    /**
     * @var ?TemplateElement $achBatchCardFee
     */
    #[JsonProperty('achBatchCardFee')]
    public ?TemplateElement $achBatchCardFee;

    /**
     * @var ?TemplateElement $annualCardFee
     */
    #[JsonProperty('annualCardFee')]
    public ?TemplateElement $annualCardFee;

    /**
     * @var ?TemplateElement $avsCardFee
     */
    #[JsonProperty('avsCardFee')]
    public ?TemplateElement $avsCardFee;

    /**
     * @var ?TemplateElement $chargebackCardFee
     */
    #[JsonProperty('chargebackCardFee')]
    public ?TemplateElement $chargebackCardFee;

    /**
     * @var ?TemplateElement $ddaRejectsCardFee
     */
    #[JsonProperty('ddaRejectsCardFee')]
    public ?TemplateElement $ddaRejectsCardFee;

    /**
     * @var ?TemplateElement $earlyTerminationCardFee
     */
    #[JsonProperty('earlyTerminationCardFee')]
    public ?TemplateElement $earlyTerminationCardFee;

    /**
     * @var ?TemplateElement $minimumProcessingCardFee
     */
    #[JsonProperty('minimumProcessingCardFee')]
    public ?TemplateElement $minimumProcessingCardFee;

    /**
     * @var ?TemplateElement $monthlyPciCardFee
     */
    #[JsonProperty('monthlyPCICardFee')]
    public ?TemplateElement $monthlyPciCardFee;

    /**
     * @var ?TemplateElement $montlyPlatformCardFee
     */
    #[JsonProperty('montlyPlatformCardFee')]
    public ?TemplateElement $montlyPlatformCardFee;

    /**
     * @var ?TemplateElement $retrievalCardFee
     */
    #[JsonProperty('retrievalCardFee')]
    public ?TemplateElement $retrievalCardFee;

    /**
     * @var ?TemplateElement $transactionCardFee
     */
    #[JsonProperty('transactionCardFee')]
    public ?TemplateElement $transactionCardFee;

    /**
     * @var ?bool $visible
     */
    #[JsonProperty('visible')]
    public ?bool $visible;

    /**
     * @param array{
     *   achBatchCardFee?: ?TemplateElement,
     *   annualCardFee?: ?TemplateElement,
     *   avsCardFee?: ?TemplateElement,
     *   chargebackCardFee?: ?TemplateElement,
     *   ddaRejectsCardFee?: ?TemplateElement,
     *   earlyTerminationCardFee?: ?TemplateElement,
     *   minimumProcessingCardFee?: ?TemplateElement,
     *   monthlyPciCardFee?: ?TemplateElement,
     *   montlyPlatformCardFee?: ?TemplateElement,
     *   retrievalCardFee?: ?TemplateElement,
     *   transactionCardFee?: ?TemplateElement,
     *   visible?: ?bool,
     * } $values
     */
    public function __construct(
        array $values = [],
    ) {
        $this->achBatchCardFee = $values['achBatchCardFee'] ?? null;
        $this->annualCardFee = $values['annualCardFee'] ?? null;
        $this->avsCardFee = $values['avsCardFee'] ?? null;
        $this->chargebackCardFee = $values['chargebackCardFee'] ?? null;
        $this->ddaRejectsCardFee = $values['ddaRejectsCardFee'] ?? null;
        $this->earlyTerminationCardFee = $values['earlyTerminationCardFee'] ?? null;
        $this->minimumProcessingCardFee = $values['minimumProcessingCardFee'] ?? null;
        $this->monthlyPciCardFee = $values['monthlyPciCardFee'] ?? null;
        $this->montlyPlatformCardFee = $values['montlyPlatformCardFee'] ?? null;
        $this->retrievalCardFee = $values['retrievalCardFee'] ?? null;
        $this->transactionCardFee = $values['transactionCardFee'] ?? null;
        $this->visible = $values['visible'] ?? null;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->toJson();
    }
}
