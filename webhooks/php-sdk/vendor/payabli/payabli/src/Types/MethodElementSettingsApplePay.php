<?php

namespace Payabli\Types;

use Payabli\Core\Json\JsonSerializableType;
use Payabli\Core\Json\JsonProperty;

class MethodElementSettingsApplePay extends JsonSerializableType
{
    /**
     * @var ?value-of<MethodElementSettingsApplePayButtonStyle> $buttonStyle The Apple Pay button style. See [Apple Pay Button Style](/developers/developer-guides/hosted-payment-page-apple-pay#param-applepay-button-style) for more information.
     */
    #[JsonProperty('buttonStyle')]
    public ?string $buttonStyle;

    /**
     * @var ?value-of<MethodElementSettingsApplePayButtonType> $buttonType The text on Apple Pay button. See [Apple Pay Button Type](/developers/developer-guides/hosted-payment-page-apple-pay#param-applepay-button-type) for more information.
     */
    #[JsonProperty('buttonType')]
    public ?string $buttonType;

    /**
     * @var ?value-of<MethodElementSettingsApplePayLanguage> $language The Apple Pay button locale. See [Apple Pay Button Language](/developers/developer-guides/hosted-payment-page-apple-pay#param-applepay-language) for more information.
     */
    #[JsonProperty('language')]
    public ?string $language;

    /**
     * @param array{
     *   buttonStyle?: ?value-of<MethodElementSettingsApplePayButtonStyle>,
     *   buttonType?: ?value-of<MethodElementSettingsApplePayButtonType>,
     *   language?: ?value-of<MethodElementSettingsApplePayLanguage>,
     * } $values
     */
    public function __construct(
        array $values = [],
    ) {
        $this->buttonStyle = $values['buttonStyle'] ?? null;
        $this->buttonType = $values['buttonType'] ?? null;
        $this->language = $values['language'] ?? null;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->toJson();
    }
}
