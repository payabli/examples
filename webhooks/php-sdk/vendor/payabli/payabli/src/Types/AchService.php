<?php

namespace Payabli\Types;

use Payabli\Core\Json\JsonSerializableType;
use Payabli\Core\Json\JsonProperty;

class AchService extends JsonSerializableType
{
    /**
     * @var ?AchAbsorbSection $achAbsorb
     */
    #[JsonProperty('achAbsorb')]
    public ?AchAbsorbSection $achAbsorb;

    /**
     * @var ?TemplateElement $achAbsorbHighPayRange
     */
    #[JsonProperty('achAbsorb_highPayRange')]
    public ?TemplateElement $achAbsorbHighPayRange;

    /**
     * @var ?TemplateElement $achAbsorbLowPayRange
     */
    #[JsonProperty('achAbsorb_lowPayRange')]
    public ?TemplateElement $achAbsorbLowPayRange;

    /**
     * @var ?AchAcceptanceElement $achAcceptance
     */
    #[JsonProperty('achAcceptance')]
    public ?AchAcceptanceElement $achAcceptance;

    /**
     * @var ?AchFeeSection $achFees
     */
    #[JsonProperty('achFees')]
    public ?AchFeeSection $achFees;

    /**
     * @var ?TemplateElement $achPassHighPayRange
     */
    #[JsonProperty('achPass_highPayRange')]
    public ?TemplateElement $achPassHighPayRange;

    /**
     * @var ?TemplateElement $achPassLowPayRange
     */
    #[JsonProperty('achPass_lowPayRange')]
    public ?TemplateElement $achPassLowPayRange;

    /**
     * @var ?AchPassThroughSection $achPassThrough
     */
    #[JsonProperty('achPassThrough')]
    public ?AchPassThroughSection $achPassThrough;

    /**
     * @var ?TemplateElement $batchCutoffTime Controls how to present the `batchCutoffTime` field on the application. If this field isn't sent, batch cut off time defaults to 5 ET.
     */
    #[JsonProperty('batchCutoffTime')]
    public ?TemplateElement $batchCutoffTime;

    /**
     * @var ?TemplateElement $discountFrequency
     */
    #[JsonProperty('discountFrequency')]
    public ?TemplateElement $discountFrequency;

    /**
     * @var ?TemplateElement $fundingRollup
     */
    #[JsonProperty('fundingRollup')]
    public ?TemplateElement $fundingRollup;

    /**
     * @var ?TemplateElement $gateway
     */
    #[JsonProperty('gateway')]
    public ?TemplateElement $gateway;

    /**
     * @var ?TemplateElement $pdfTemplateId
     */
    #[JsonProperty('pdfTemplateId')]
    public ?TemplateElement $pdfTemplateId;

    /**
     * @var ?int $pricingPlan
     */
    #[JsonProperty('pricingPlan')]
    public ?int $pricingPlan;

    /**
     * @var ?TemplateElement $pricingType
     */
    #[JsonProperty('pricingType')]
    public ?TemplateElement $pricingType;

    /**
     * @var ?TemplateElement $processor
     */
    #[JsonProperty('processor')]
    public ?TemplateElement $processor;

    /**
     * @var ?TemplateElement $provider
     */
    #[JsonProperty('provider')]
    public ?TemplateElement $provider;

    /**
     * @var ?TemplateElement $tierName
     */
    #[JsonProperty('tierName')]
    public ?TemplateElement $tierName;

    /**
     * @var ?bool $visible
     */
    #[JsonProperty('visible')]
    public ?bool $visible;

    /**
     * @param array{
     *   achAbsorb?: ?AchAbsorbSection,
     *   achAbsorbHighPayRange?: ?TemplateElement,
     *   achAbsorbLowPayRange?: ?TemplateElement,
     *   achAcceptance?: ?AchAcceptanceElement,
     *   achFees?: ?AchFeeSection,
     *   achPassHighPayRange?: ?TemplateElement,
     *   achPassLowPayRange?: ?TemplateElement,
     *   achPassThrough?: ?AchPassThroughSection,
     *   batchCutoffTime?: ?TemplateElement,
     *   discountFrequency?: ?TemplateElement,
     *   fundingRollup?: ?TemplateElement,
     *   gateway?: ?TemplateElement,
     *   pdfTemplateId?: ?TemplateElement,
     *   pricingPlan?: ?int,
     *   pricingType?: ?TemplateElement,
     *   processor?: ?TemplateElement,
     *   provider?: ?TemplateElement,
     *   tierName?: ?TemplateElement,
     *   visible?: ?bool,
     * } $values
     */
    public function __construct(
        array $values = [],
    ) {
        $this->achAbsorb = $values['achAbsorb'] ?? null;
        $this->achAbsorbHighPayRange = $values['achAbsorbHighPayRange'] ?? null;
        $this->achAbsorbLowPayRange = $values['achAbsorbLowPayRange'] ?? null;
        $this->achAcceptance = $values['achAcceptance'] ?? null;
        $this->achFees = $values['achFees'] ?? null;
        $this->achPassHighPayRange = $values['achPassHighPayRange'] ?? null;
        $this->achPassLowPayRange = $values['achPassLowPayRange'] ?? null;
        $this->achPassThrough = $values['achPassThrough'] ?? null;
        $this->batchCutoffTime = $values['batchCutoffTime'] ?? null;
        $this->discountFrequency = $values['discountFrequency'] ?? null;
        $this->fundingRollup = $values['fundingRollup'] ?? null;
        $this->gateway = $values['gateway'] ?? null;
        $this->pdfTemplateId = $values['pdfTemplateId'] ?? null;
        $this->pricingPlan = $values['pricingPlan'] ?? null;
        $this->pricingType = $values['pricingType'] ?? null;
        $this->processor = $values['processor'] ?? null;
        $this->provider = $values['provider'] ?? null;
        $this->tierName = $values['tierName'] ?? null;
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
