<?php

namespace Payabli\Subscription\Requests;

use Payabli\Core\Json\JsonSerializableType;
use Payabli\Types\PaymentDetail;
use Payabli\Core\Json\JsonProperty;
use Payabli\Types\ScheduleDetail;

class RequestUpdateSchedule extends JsonSerializableType
{
    /**
     * @var ?PaymentDetail $paymentDetails Object describing details of the payment. To skip the payment, set the `totalAmount` to 0. Payments will be paused until the amount is updated to a non-zero value. When `totalAmount` is set to 0, the `serviceFee` must also be set to 0.
     */
    #[JsonProperty('paymentDetails')]
    public ?PaymentDetail $paymentDetails;

    /**
     * @var ?ScheduleDetail $scheduleDetails Object describing the schedule for subscription
     */
    #[JsonProperty('scheduleDetails')]
    public ?ScheduleDetail $scheduleDetails;

    /**
     * @var ?bool $setPause
     */
    #[JsonProperty('setPause')]
    public ?bool $setPause;

    /**
     * @param array{
     *   paymentDetails?: ?PaymentDetail,
     *   scheduleDetails?: ?ScheduleDetail,
     *   setPause?: ?bool,
     * } $values
     */
    public function __construct(
        array $values = [],
    ) {
        $this->paymentDetails = $values['paymentDetails'] ?? null;
        $this->scheduleDetails = $values['scheduleDetails'] ?? null;
        $this->setPause = $values['setPause'] ?? null;
    }
}
