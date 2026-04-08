<?php

namespace Payabli\Types;

use Payabli\Core\Json\JsonSerializableType;
use Payabli\Core\Json\JsonProperty;
use Payabli\Core\Types\ArrayType;

class SettingElement extends JsonSerializableType
{
    /**
     * @var ?bool $enabled
     */
    #[JsonProperty('enabled')]
    public ?bool $enabled;

    /**
     * @var ?array<DisplayProperty> $fields Fields to display on the reciept.
     */
    #[JsonProperty('fields'), ArrayType([DisplayProperty::class])]
    public ?array $fields;

    /**
     * @var ?int $order
     */
    #[JsonProperty('order')]
    public ?int $order;

    /**
     * @var ?bool $sendAuto When `true`, Payabli automatically sends the receipt to the payor email address.
     */
    #[JsonProperty('sendAuto')]
    public ?bool $sendAuto;

    /**
     * @var ?bool $sendManual When `true`, you must send the reciept to the payor manually using the [/MoneyIn/sendreceipt/\{transId\}](/api-reference/moneyin/send-receipt-for-transaction) endpoint.
     */
    #[JsonProperty('sendManual')]
    public ?bool $sendManual;

    /**
     * @param array{
     *   enabled?: ?bool,
     *   fields?: ?array<DisplayProperty>,
     *   order?: ?int,
     *   sendAuto?: ?bool,
     *   sendManual?: ?bool,
     * } $values
     */
    public function __construct(
        array $values = [],
    ) {
        $this->enabled = $values['enabled'] ?? null;
        $this->fields = $values['fields'] ?? null;
        $this->order = $values['order'] ?? null;
        $this->sendAuto = $values['sendAuto'] ?? null;
        $this->sendManual = $values['sendManual'] ?? null;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->toJson();
    }
}
