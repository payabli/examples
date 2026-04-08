<?php

namespace Payabli\PaymentLink\Requests;

use Payabli\Core\Json\JsonSerializableType;
use Payabli\Types\ContactElement;
use Payabli\Core\Json\JsonProperty;
use Payabli\Types\Element;
use Payabli\Types\LabelElement;
use Payabli\Types\NoteElement;
use Payabli\Types\PageElement;
use Payabli\Types\MethodElement;
use Payabli\Types\HeaderElement;
use Payabli\Types\PagelinkSetting;

class PayLinkUpdateData extends JsonSerializableType
{
    /**
     * @var ?ContactElement $contactUs ContactUs section of payment link page
     */
    #[JsonProperty('contactUs')]
    public ?ContactElement $contactUs;

    /**
     * @var ?Element $logo Logo section of payment link page
     */
    #[JsonProperty('logo')]
    public ?Element $logo;

    /**
     * @var ?LabelElement $messageBeforePaying Message section of payment link page
     */
    #[JsonProperty('messageBeforePaying')]
    public ?LabelElement $messageBeforePaying;

    /**
     * @var ?NoteElement $notes Notes section of payment link page
     */
    #[JsonProperty('notes')]
    public ?NoteElement $notes;

    /**
     * @var ?PageElement $page Page header section of payment link page
     */
    #[JsonProperty('page')]
    public ?PageElement $page;

    /**
     * @var ?LabelElement $paymentButton Payment button section of payment link page
     */
    #[JsonProperty('paymentButton')]
    public ?LabelElement $paymentButton;

    /**
     * @var ?MethodElement $paymentMethods Payment methods section of payment link page
     */
    #[JsonProperty('paymentMethods')]
    public ?MethodElement $paymentMethods;

    /**
     * @var ?HeaderElement $review Review section of payment link page
     */
    #[JsonProperty('review')]
    public ?HeaderElement $review;

    /**
     * @var ?PagelinkSetting $settings Settings section of payment link page
     */
    #[JsonProperty('settings')]
    public ?PagelinkSetting $settings;

    /**
     * @param array{
     *   contactUs?: ?ContactElement,
     *   logo?: ?Element,
     *   messageBeforePaying?: ?LabelElement,
     *   notes?: ?NoteElement,
     *   page?: ?PageElement,
     *   paymentButton?: ?LabelElement,
     *   paymentMethods?: ?MethodElement,
     *   review?: ?HeaderElement,
     *   settings?: ?PagelinkSetting,
     * } $values
     */
    public function __construct(
        array $values = [],
    ) {
        $this->contactUs = $values['contactUs'] ?? null;
        $this->logo = $values['logo'] ?? null;
        $this->messageBeforePaying = $values['messageBeforePaying'] ?? null;
        $this->notes = $values['notes'] ?? null;
        $this->page = $values['page'] ?? null;
        $this->paymentButton = $values['paymentButton'] ?? null;
        $this->paymentMethods = $values['paymentMethods'] ?? null;
        $this->review = $values['review'] ?? null;
        $this->settings = $values['settings'] ?? null;
    }
}
