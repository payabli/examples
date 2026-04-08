<?php

namespace Payabli\MoneyIn\Requests;

use Payabli\Core\Json\JsonSerializableType;
use Payabli\MoneyIn\Types\TransRequestBody;

class RequestPayment extends JsonSerializableType
{
    /**
     * @var ?bool $achValidation
     */
    public ?bool $achValidation;

    /**
     * @var ?bool $forceCustomerCreation
     */
    public ?bool $forceCustomerCreation;

    /**
     * @var ?string $idempotencyKey
     */
    public ?string $idempotencyKey;

    /**
     * @var ?string $validationCode Value obtained from user when an API generated CAPTCHA is used in payment page
     */
    public ?string $validationCode;

    /**
     * @var TransRequestBody $body
     */
    public TransRequestBody $body;

    /**
     * @param array{
     *   body: TransRequestBody,
     *   achValidation?: ?bool,
     *   forceCustomerCreation?: ?bool,
     *   idempotencyKey?: ?string,
     *   validationCode?: ?string,
     * } $values
     */
    public function __construct(
        array $values,
    ) {
        $this->achValidation = $values['achValidation'] ?? null;
        $this->forceCustomerCreation = $values['forceCustomerCreation'] ?? null;
        $this->idempotencyKey = $values['idempotencyKey'] ?? null;
        $this->validationCode = $values['validationCode'] ?? null;
        $this->body = $values['body'];
    }
}
