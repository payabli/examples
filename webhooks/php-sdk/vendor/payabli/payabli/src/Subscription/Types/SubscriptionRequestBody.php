<?php

namespace Payabli\Subscription\Types;

use Payabli\Core\Json\JsonSerializableType;
use Payabli\Types\PayorDataRequest;
use Payabli\Core\Json\JsonProperty;
use Payabli\Types\BillData;
use Payabli\Types\PaymentDetail;
use Payabli\Types\PayMethodCredit;
use Payabli\Types\PayMethodAch;
use Payabli\Core\Types\Union;
use Payabli\Types\ScheduleDetail;

class SubscriptionRequestBody extends JsonSerializableType
{
    /**
     * @var ?PayorDataRequest $customerData Object describing the customer/payor.
     */
    #[JsonProperty('customerData')]
    public ?PayorDataRequest $customerData;

    /**
     * @var ?string $entryPoint
     */
    #[JsonProperty('entryPoint')]
    public ?string $entryPoint;

    /**
     * @var ?BillData $invoiceData Object describing an Invoice linked to the subscription.
     */
    #[JsonProperty('invoiceData')]
    public ?BillData $invoiceData;

    /**
     * @var ?PaymentDetail $paymentDetails Object describing details of the payment. To skip the payment, set the `totalAmount` to 0. Payments will be paused until the amount is updated to a non-zero value. When `totalAmount` is set to 0, the `serviceFee` must also be set to 0.
     */
    #[JsonProperty('paymentDetails')]
    public ?PaymentDetail $paymentDetails;

    /**
     * @var (
     *    PayMethodCredit
     *   |PayMethodAch
     *   |RequestSchedulePaymentMethodInitiator
     * )|null $paymentMethod Information about the payment method for the transaction. Required and recommended fields for each payment method type are described in each schema below.
     */
    #[JsonProperty('paymentMethod'), Union(PayMethodCredit::class, PayMethodAch::class, RequestSchedulePaymentMethodInitiator::class, 'null')]
    public PayMethodCredit|PayMethodAch|RequestSchedulePaymentMethodInitiator|null $paymentMethod;

    /**
     * @var ?ScheduleDetail $scheduleDetails Object describing the schedule for subscription.
     */
    #[JsonProperty('scheduleDetails')]
    public ?ScheduleDetail $scheduleDetails;

    /**
     * @var ?bool $setPause
     */
    #[JsonProperty('setPause')]
    public ?bool $setPause;

    /**
     * @var ?string $source
     */
    #[JsonProperty('source')]
    public ?string $source;

    /**
     * @var ?string $subdomain
     */
    #[JsonProperty('subdomain')]
    public ?string $subdomain;

    /**
     * @param array{
     *   customerData?: ?PayorDataRequest,
     *   entryPoint?: ?string,
     *   invoiceData?: ?BillData,
     *   paymentDetails?: ?PaymentDetail,
     *   paymentMethod?: (
     *    PayMethodCredit
     *   |PayMethodAch
     *   |RequestSchedulePaymentMethodInitiator
     * )|null,
     *   scheduleDetails?: ?ScheduleDetail,
     *   setPause?: ?bool,
     *   source?: ?string,
     *   subdomain?: ?string,
     * } $values
     */
    public function __construct(
        array $values = [],
    ) {
        $this->customerData = $values['customerData'] ?? null;
        $this->entryPoint = $values['entryPoint'] ?? null;
        $this->invoiceData = $values['invoiceData'] ?? null;
        $this->paymentDetails = $values['paymentDetails'] ?? null;
        $this->paymentMethod = $values['paymentMethod'] ?? null;
        $this->scheduleDetails = $values['scheduleDetails'] ?? null;
        $this->setPause = $values['setPause'] ?? null;
        $this->source = $values['source'] ?? null;
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
