<?php

namespace Payabli\Types;

use Payabli\Core\Json\JsonSerializableType;
use Payabli\Core\Json\JsonProperty;

class AchFeeSection extends JsonSerializableType
{
    /**
     * @var ?TemplateElement $advancedSettlementAchFee
     */
    #[JsonProperty('advancedSettlementAchFee')]
    public ?TemplateElement $advancedSettlementAchFee;

    /**
     * @var ?TemplateElement $annualAchFee
     */
    #[JsonProperty('annualAchFee')]
    public ?TemplateElement $annualAchFee;

    /**
     * @var ?TemplateElement $chargebackAchFee
     */
    #[JsonProperty('chargebackAchFee')]
    public ?TemplateElement $chargebackAchFee;

    /**
     * @var ?TemplateElement $earlyTerminationAchFee
     */
    #[JsonProperty('earlyTerminationAchFee')]
    public ?TemplateElement $earlyTerminationAchFee;

    /**
     * @var ?TemplateElement $monthlyAchFee
     */
    #[JsonProperty('monthlyAchFee')]
    public ?TemplateElement $monthlyAchFee;

    /**
     * @var ?TemplateElement $quarterlyPciAchFee
     */
    #[JsonProperty('quarterlyPCIAchFee')]
    public ?TemplateElement $quarterlyPciAchFee;

    /**
     * @var ?TemplateElement $returnedAchFee
     */
    #[JsonProperty('returnedAchFee')]
    public ?TemplateElement $returnedAchFee;

    /**
     * @var ?TemplateElement $sameDayAchFee
     */
    #[JsonProperty('sameDayAchFee')]
    public ?TemplateElement $sameDayAchFee;

    /**
     * @var ?TemplateElement $sundayOriginationAchFee
     */
    #[JsonProperty('sundayOriginationAchFee')]
    public ?TemplateElement $sundayOriginationAchFee;

    /**
     * @var ?TemplateElement $verifyBankAchFee
     */
    #[JsonProperty('verifyBankAchFee')]
    public ?TemplateElement $verifyBankAchFee;

    /**
     * @var ?TemplateElement $verifyFundAchFee
     */
    #[JsonProperty('verifyFundAchFee')]
    public ?TemplateElement $verifyFundAchFee;

    /**
     * @var ?TemplateElement $verifyNegativeAchFee
     */
    #[JsonProperty('verifyNegativeAchFee')]
    public ?TemplateElement $verifyNegativeAchFee;

    /**
     * @var ?bool $visible
     */
    #[JsonProperty('visible')]
    public ?bool $visible;

    /**
     * @param array{
     *   advancedSettlementAchFee?: ?TemplateElement,
     *   annualAchFee?: ?TemplateElement,
     *   chargebackAchFee?: ?TemplateElement,
     *   earlyTerminationAchFee?: ?TemplateElement,
     *   monthlyAchFee?: ?TemplateElement,
     *   quarterlyPciAchFee?: ?TemplateElement,
     *   returnedAchFee?: ?TemplateElement,
     *   sameDayAchFee?: ?TemplateElement,
     *   sundayOriginationAchFee?: ?TemplateElement,
     *   verifyBankAchFee?: ?TemplateElement,
     *   verifyFundAchFee?: ?TemplateElement,
     *   verifyNegativeAchFee?: ?TemplateElement,
     *   visible?: ?bool,
     * } $values
     */
    public function __construct(
        array $values = [],
    ) {
        $this->advancedSettlementAchFee = $values['advancedSettlementAchFee'] ?? null;
        $this->annualAchFee = $values['annualAchFee'] ?? null;
        $this->chargebackAchFee = $values['chargebackAchFee'] ?? null;
        $this->earlyTerminationAchFee = $values['earlyTerminationAchFee'] ?? null;
        $this->monthlyAchFee = $values['monthlyAchFee'] ?? null;
        $this->quarterlyPciAchFee = $values['quarterlyPciAchFee'] ?? null;
        $this->returnedAchFee = $values['returnedAchFee'] ?? null;
        $this->sameDayAchFee = $values['sameDayAchFee'] ?? null;
        $this->sundayOriginationAchFee = $values['sundayOriginationAchFee'] ?? null;
        $this->verifyBankAchFee = $values['verifyBankAchFee'] ?? null;
        $this->verifyFundAchFee = $values['verifyFundAchFee'] ?? null;
        $this->verifyNegativeAchFee = $values['verifyNegativeAchFee'] ?? null;
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
