<?php

namespace Payabli\PaymentLink\Types;

use Payabli\Core\Json\JsonSerializableType;
use Payabli\Types\ContactElement;
use Payabli\Core\Json\JsonProperty;
use Payabli\Types\InvoiceElement;
use Payabli\Types\Element;
use Payabli\Types\LabelElement;
use Payabli\Types\NoteElement;
use Payabli\Types\PageElement;
use Payabli\Types\MethodElement;
use Payabli\Types\PayorElement;
use Payabli\Types\HeaderElement;
use Payabli\Types\PagelinkSetting;

class PaymentPageRequestBody extends JsonSerializableType
{
    /**
     * @var ?ContactElement $contactUs ContactUs section of payment link page
     */
    #[JsonProperty('contactUs')]
    public ?ContactElement $contactUs;

    /**
     * @var ?InvoiceElement $invoices Invoices section of payment link page
     */
    #[JsonProperty('invoices')]
    public ?InvoiceElement $invoices;

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
     * @var ?PayorElement $payor Customer/Payor section of payment link page
     */
    #[JsonProperty('payor')]
    public ?PayorElement $payor;

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
     *   invoices?: ?InvoiceElement,
     *   logo?: ?Element,
     *   messageBeforePaying?: ?LabelElement,
     *   notes?: ?NoteElement,
     *   page?: ?PageElement,
     *   paymentButton?: ?LabelElement,
     *   paymentMethods?: ?MethodElement,
     *   payor?: ?PayorElement,
     *   review?: ?HeaderElement,
     *   settings?: ?PagelinkSetting,
     * } $values
     */
    public function __construct(
        array $values = [],
    ) {
        $this->contactUs = $values['contactUs'] ?? null;
        $this->invoices = $values['invoices'] ?? null;
        $this->logo = $values['logo'] ?? null;
        $this->messageBeforePaying = $values['messageBeforePaying'] ?? null;
        $this->notes = $values['notes'] ?? null;
        $this->page = $values['page'] ?? null;
        $this->paymentButton = $values['paymentButton'] ?? null;
        $this->paymentMethods = $values['paymentMethods'] ?? null;
        $this->payor = $values['payor'] ?? null;
        $this->review = $values['review'] ?? null;
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
