<?php

namespace Payabli\Types;

use Payabli\Core\Json\JsonSerializableType;
use Payabli\Core\Json\JsonProperty;

class PageContent extends JsonSerializableType
{
    /**
     * @var ?AmountElement $amount Amount section of payment page
     */
    #[JsonProperty('amount')]
    public ?AmountElement $amount;

    /**
     * @var ?AutoElement $autopay Autopay section of payment page
     */
    #[JsonProperty('autopay')]
    public ?AutoElement $autopay;

    /**
     * @var ?ContactElement $contactUs ContactUs section of payment page
     */
    #[JsonProperty('contactUs')]
    public ?ContactElement $contactUs;

    /**
     * @var ?string $entry Identifier of entry point owner of page
     */
    #[JsonProperty('entry')]
    public ?string $entry;

    /**
     * @var ?InvoiceElement $invoices Invoices section of payment page
     */
    #[JsonProperty('invoices')]
    public ?InvoiceElement $invoices;

    /**
     * @var ?Element $logo Logo section of payment page
     */
    #[JsonProperty('logo')]
    public ?Element $logo;

    /**
     * @var ?LabelElement $messageBeforePaying Message section of payment page
     */
    #[JsonProperty('messageBeforePaying')]
    public ?LabelElement $messageBeforePaying;

    /**
     * @var ?string $name Descriptor of page
     */
    #[JsonProperty('name')]
    public ?string $name;

    /**
     * @var ?NoteElement $notes Notes section of payment page
     */
    #[JsonProperty('notes')]
    public ?NoteElement $notes;

    /**
     * @var ?PageElement $page Page header section of payment page
     */
    #[JsonProperty('page')]
    public ?PageElement $page;

    /**
     * @var ?LabelElement $paymentButton Payment button section of payment page
     */
    #[JsonProperty('paymentButton')]
    public ?LabelElement $paymentButton;

    /**
     * @var ?MethodElement $paymentMethods Payment methods section of payment page
     */
    #[JsonProperty('paymentMethods')]
    public ?MethodElement $paymentMethods;

    /**
     * @var ?PayorElement $payor Customer/Payor section of payment page
     */
    #[JsonProperty('payor')]
    public ?PayorElement $payor;

    /**
     * @var ?HeaderElement $review Review section of payment page
     */
    #[JsonProperty('review')]
    public ?HeaderElement $review;

    /**
     * @var ?string $subdomain Unique identifier assigned to the page.
     */
    #[JsonProperty('subdomain')]
    public ?string $subdomain;

    /**
     * @param array{
     *   amount?: ?AmountElement,
     *   autopay?: ?AutoElement,
     *   contactUs?: ?ContactElement,
     *   entry?: ?string,
     *   invoices?: ?InvoiceElement,
     *   logo?: ?Element,
     *   messageBeforePaying?: ?LabelElement,
     *   name?: ?string,
     *   notes?: ?NoteElement,
     *   page?: ?PageElement,
     *   paymentButton?: ?LabelElement,
     *   paymentMethods?: ?MethodElement,
     *   payor?: ?PayorElement,
     *   review?: ?HeaderElement,
     *   subdomain?: ?string,
     * } $values
     */
    public function __construct(
        array $values = [],
    ) {
        $this->amount = $values['amount'] ?? null;
        $this->autopay = $values['autopay'] ?? null;
        $this->contactUs = $values['contactUs'] ?? null;
        $this->entry = $values['entry'] ?? null;
        $this->invoices = $values['invoices'] ?? null;
        $this->logo = $values['logo'] ?? null;
        $this->messageBeforePaying = $values['messageBeforePaying'] ?? null;
        $this->name = $values['name'] ?? null;
        $this->notes = $values['notes'] ?? null;
        $this->page = $values['page'] ?? null;
        $this->paymentButton = $values['paymentButton'] ?? null;
        $this->paymentMethods = $values['paymentMethods'] ?? null;
        $this->payor = $values['payor'] ?? null;
        $this->review = $values['review'] ?? null;
        $this->subdomain = $values['subdomain'] ?? null;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->toJson();
    }
}
