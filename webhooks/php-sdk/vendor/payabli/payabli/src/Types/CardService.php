<?php

namespace Payabli\Types;

use Payabli\Core\Json\JsonSerializableType;
use Payabli\Core\Json\JsonProperty;

class CardService extends JsonSerializableType
{
    /**
     * @var ?TemplateElement $batchCutoffTime Controls how to present the `batchCutoffTime` field on the application. If this field isn't sent, batch cut off time defaults to 5 ET.
     */
    #[JsonProperty('batchCutoffTime')]
    public ?TemplateElement $batchCutoffTime;

    /**
     * @var ?CardAcceptanceElement $cardAcceptance
     */
    #[JsonProperty('cardAcceptance')]
    public ?CardAcceptanceElement $cardAcceptance;

    /**
     * @var ?CardFeeSection $cardFees
     */
    #[JsonProperty('cardFees')]
    public ?CardFeeSection $cardFees;

    /**
     * @var ?CardFlatSection $cardFlat
     */
    #[JsonProperty('cardFlat')]
    public ?CardFlatSection $cardFlat;

    /**
     * @var ?TemplateElement $cardFlatAmountxAuth
     */
    #[JsonProperty('cardFlat_amountxAuth')]
    public ?TemplateElement $cardFlatAmountxAuth;

    /**
     * @var ?TemplateElement $cardFlatHighPayRange
     */
    #[JsonProperty('cardFlat_highPayRange')]
    public ?TemplateElement $cardFlatHighPayRange;

    /**
     * @var ?TemplateElement $cardFlatLowPayRange
     */
    #[JsonProperty('cardFlat_lowPayRange')]
    public ?TemplateElement $cardFlatLowPayRange;

    /**
     * @var ?TemplateElement $cardFlatPercentxAuth
     */
    #[JsonProperty('cardFlat_percentxAuth')]
    public ?TemplateElement $cardFlatPercentxAuth;

    /**
     * @var ?CardIcpSection $cardIcp
     */
    #[JsonProperty('cardICP')]
    public ?CardIcpSection $cardIcp;

    /**
     * @var ?TemplateElement $cardIcpAmountxAuth
     */
    #[JsonProperty('cardICP_amountxAuth')]
    public ?TemplateElement $cardIcpAmountxAuth;

    /**
     * @var ?TemplateElement $cardIcpHighPayRange
     */
    #[JsonProperty('cardICP_highPayRange')]
    public ?TemplateElement $cardIcpHighPayRange;

    /**
     * @var ?TemplateElement $cardIcpLowPayRange
     */
    #[JsonProperty('cardICP_lowPayRange')]
    public ?TemplateElement $cardIcpLowPayRange;

    /**
     * @var ?TemplateElement $cardIcpPercentxAuth
     */
    #[JsonProperty('cardICP_percentxAuth')]
    public ?TemplateElement $cardIcpPercentxAuth;

    /**
     * @var ?CardPassThroughSection $cardPassThrough
     */
    #[JsonProperty('cardPassThrough')]
    public ?CardPassThroughSection $cardPassThrough;

    /**
     * @var ?TemplateElement $cardPassThroughAmountRecurring
     */
    #[JsonProperty('cardPassThrough_amountRecurring')]
    public ?TemplateElement $cardPassThroughAmountRecurring;

    /**
     * @var ?TemplateElement $cardPassThroughAmountxAuth
     */
    #[JsonProperty('cardPassThrough_amountxAuth')]
    public ?TemplateElement $cardPassThroughAmountxAuth;

    /**
     * @var ?TemplateElement $cardPassThroughHighPayRange
     */
    #[JsonProperty('cardPassThrough_highPayRange')]
    public ?TemplateElement $cardPassThroughHighPayRange;

    /**
     * @var ?TemplateElement $cardPassThroughLowPayRange
     */
    #[JsonProperty('cardPassThrough_lowPayRange')]
    public ?TemplateElement $cardPassThroughLowPayRange;

    /**
     * @var ?TemplateElement $cardPassThroughPercentRecurring
     */
    #[JsonProperty('cardPassThrough_percentRecurring')]
    public ?TemplateElement $cardPassThroughPercentRecurring;

    /**
     * @var ?TemplateElement $cardPassThroughPercentxAuth
     */
    #[JsonProperty('cardPassThrough_percentxAuth')]
    public ?TemplateElement $cardPassThroughPercentxAuth;

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
     * @var ?TemplateElement $passThroughCost
     */
    #[JsonProperty('passThroughCost')]
    public ?TemplateElement $passThroughCost;

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
     *   batchCutoffTime?: ?TemplateElement,
     *   cardAcceptance?: ?CardAcceptanceElement,
     *   cardFees?: ?CardFeeSection,
     *   cardFlat?: ?CardFlatSection,
     *   cardFlatAmountxAuth?: ?TemplateElement,
     *   cardFlatHighPayRange?: ?TemplateElement,
     *   cardFlatLowPayRange?: ?TemplateElement,
     *   cardFlatPercentxAuth?: ?TemplateElement,
     *   cardIcp?: ?CardIcpSection,
     *   cardIcpAmountxAuth?: ?TemplateElement,
     *   cardIcpHighPayRange?: ?TemplateElement,
     *   cardIcpLowPayRange?: ?TemplateElement,
     *   cardIcpPercentxAuth?: ?TemplateElement,
     *   cardPassThrough?: ?CardPassThroughSection,
     *   cardPassThroughAmountRecurring?: ?TemplateElement,
     *   cardPassThroughAmountxAuth?: ?TemplateElement,
     *   cardPassThroughHighPayRange?: ?TemplateElement,
     *   cardPassThroughLowPayRange?: ?TemplateElement,
     *   cardPassThroughPercentRecurring?: ?TemplateElement,
     *   cardPassThroughPercentxAuth?: ?TemplateElement,
     *   discountFrequency?: ?TemplateElement,
     *   fundingRollup?: ?TemplateElement,
     *   gateway?: ?TemplateElement,
     *   passThroughCost?: ?TemplateElement,
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
        $this->batchCutoffTime = $values['batchCutoffTime'] ?? null;
        $this->cardAcceptance = $values['cardAcceptance'] ?? null;
        $this->cardFees = $values['cardFees'] ?? null;
        $this->cardFlat = $values['cardFlat'] ?? null;
        $this->cardFlatAmountxAuth = $values['cardFlatAmountxAuth'] ?? null;
        $this->cardFlatHighPayRange = $values['cardFlatHighPayRange'] ?? null;
        $this->cardFlatLowPayRange = $values['cardFlatLowPayRange'] ?? null;
        $this->cardFlatPercentxAuth = $values['cardFlatPercentxAuth'] ?? null;
        $this->cardIcp = $values['cardIcp'] ?? null;
        $this->cardIcpAmountxAuth = $values['cardIcpAmountxAuth'] ?? null;
        $this->cardIcpHighPayRange = $values['cardIcpHighPayRange'] ?? null;
        $this->cardIcpLowPayRange = $values['cardIcpLowPayRange'] ?? null;
        $this->cardIcpPercentxAuth = $values['cardIcpPercentxAuth'] ?? null;
        $this->cardPassThrough = $values['cardPassThrough'] ?? null;
        $this->cardPassThroughAmountRecurring = $values['cardPassThroughAmountRecurring'] ?? null;
        $this->cardPassThroughAmountxAuth = $values['cardPassThroughAmountxAuth'] ?? null;
        $this->cardPassThroughHighPayRange = $values['cardPassThroughHighPayRange'] ?? null;
        $this->cardPassThroughLowPayRange = $values['cardPassThroughLowPayRange'] ?? null;
        $this->cardPassThroughPercentRecurring = $values['cardPassThroughPercentRecurring'] ?? null;
        $this->cardPassThroughPercentxAuth = $values['cardPassThroughPercentxAuth'] ?? null;
        $this->discountFrequency = $values['discountFrequency'] ?? null;
        $this->fundingRollup = $values['fundingRollup'] ?? null;
        $this->gateway = $values['gateway'] ?? null;
        $this->passThroughCost = $values['passThroughCost'] ?? null;
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
