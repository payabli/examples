<?php

namespace Payabli\Types;

use Payabli\Core\Json\JsonSerializableType;
use Payabli\Core\Json\JsonProperty;

/**
 * Object containing receipt body configuration
 */
class ReceiptContent extends JsonSerializableType
{
    /**
     * @var ?Element $amount Section amount of payment receipt
     */
    #[JsonProperty('amount')]
    public ?Element $amount;

    /**
     * @var ?Element $contactUs Section contactUs of payment receipt
     */
    #[JsonProperty('contactUs')]
    public ?Element $contactUs;

    /**
     * @var ?Element $details Section payment details of payment receipt
     */
    #[JsonProperty('details')]
    public ?Element $details;

    /**
     * @var ?Element $logo Section logo of payment receipt
     */
    #[JsonProperty('logo')]
    public ?Element $logo;

    /**
     * @var ?LabelElement $messageBeforeButton Section message of payment receipt
     */
    #[JsonProperty('messageBeforeButton')]
    public ?LabelElement $messageBeforeButton;

    /**
     * @var ?PageElement $page Section page of payment receipt
     */
    #[JsonProperty('page')]
    public ?PageElement $page;

    /**
     * @var ?LabelElement $paymentButton Section payment button of payment receipt
     */
    #[JsonProperty('paymentButton')]
    public ?LabelElement $paymentButton;

    /**
     * @var ?Element $paymentInformation Section payment information of payment receipt
     */
    #[JsonProperty('paymentInformation')]
    public ?Element $paymentInformation;

    /**
     * @var ?SettingElement $settings The receipt's settings.
     */
    #[JsonProperty('settings')]
    public ?SettingElement $settings;

    /**
     * @param array{
     *   amount?: ?Element,
     *   contactUs?: ?Element,
     *   details?: ?Element,
     *   logo?: ?Element,
     *   messageBeforeButton?: ?LabelElement,
     *   page?: ?PageElement,
     *   paymentButton?: ?LabelElement,
     *   paymentInformation?: ?Element,
     *   settings?: ?SettingElement,
     * } $values
     */
    public function __construct(
        array $values = [],
    ) {
        $this->amount = $values['amount'] ?? null;
        $this->contactUs = $values['contactUs'] ?? null;
        $this->details = $values['details'] ?? null;
        $this->logo = $values['logo'] ?? null;
        $this->messageBeforeButton = $values['messageBeforeButton'] ?? null;
        $this->page = $values['page'] ?? null;
        $this->paymentButton = $values['paymentButton'] ?? null;
        $this->paymentInformation = $values['paymentInformation'] ?? null;
        $this->settings = $values['settings'] ?? null;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->toJson();
    }
}
