<?php

namespace Payabli\PaymentLink\Types;

use Payabli\Core\Json\JsonSerializableType;
use Payabli\Traits\PayabliPages;
use Payabli\Types\PayabliCredentials;
use DateTime;
use Payabli\Types\PageContent;
use Payabli\Types\PageSetting;
use Payabli\Types\ReceiptContent;

class GetPayLinkFromIdResponseResponseData extends JsonSerializableType
{
    use PayabliPages;


    /**
     * @param array{
     *   additionalData?: ?array<string, ?array<string, mixed>>,
     *   credentials?: ?array<PayabliCredentials>,
     *   lastAccess?: ?DateTime,
     *   pageContent?: ?PageContent,
     *   pageIdentifier?: ?string,
     *   pageSettings?: ?PageSetting,
     *   published?: ?int,
     *   receiptContent?: ?ReceiptContent,
     *   subdomain?: ?string,
     *   totalAmount?: ?float,
     *   validationCode?: ?string,
     * } $values
     */
    public function __construct(
        array $values = [],
    ) {
        $this->additionalData = $values['additionalData'] ?? null;
        $this->credentials = $values['credentials'] ?? null;
        $this->lastAccess = $values['lastAccess'] ?? null;
        $this->pageContent = $values['pageContent'] ?? null;
        $this->pageIdentifier = $values['pageIdentifier'] ?? null;
        $this->pageSettings = $values['pageSettings'] ?? null;
        $this->published = $values['published'] ?? null;
        $this->receiptContent = $values['receiptContent'] ?? null;
        $this->subdomain = $values['subdomain'] ?? null;
        $this->totalAmount = $values['totalAmount'] ?? null;
        $this->validationCode = $values['validationCode'] ?? null;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->toJson();
    }
}
