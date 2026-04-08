<?php

namespace Payabli\Types;

use Payabli\Core\Json\JsonSerializableType;
use Payabli\Core\Json\JsonProperty;

class CustomerQueryRecordsCustomerConsent extends JsonSerializableType
{
    /**
     * @var ?CustomerQueryRecordsCustomerConsentECommunication $eCommunication Describes the customer's email communications consent status.
     */
    #[JsonProperty('eCommunication')]
    public ?CustomerQueryRecordsCustomerConsentECommunication $eCommunication;

    /**
     * @var ?CustomerQueryRecordsCustomerConsentSms $sms Describes the customer's SMS communications consent status.
     */
    #[JsonProperty('sms')]
    public ?CustomerQueryRecordsCustomerConsentSms $sms;

    /**
     * @param array{
     *   eCommunication?: ?CustomerQueryRecordsCustomerConsentECommunication,
     *   sms?: ?CustomerQueryRecordsCustomerConsentSms,
     * } $values
     */
    public function __construct(
        array $values = [],
    ) {
        $this->eCommunication = $values['eCommunication'] ?? null;
        $this->sms = $values['sms'] ?? null;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->toJson();
    }
}
