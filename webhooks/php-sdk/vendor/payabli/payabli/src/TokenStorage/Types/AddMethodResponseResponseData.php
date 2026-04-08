<?php

namespace Payabli\TokenStorage\Types;

use Payabli\Core\Json\JsonSerializableType;
use Payabli\Core\Json\JsonProperty;

class AddMethodResponseResponseData extends JsonSerializableType
{
    /**
     * @var ?string $referenceId Stored method identifier in Payabli platform. This ID is used to manage the stored method.
     */
    #[JsonProperty('referenceId')]
    public ?string $referenceId;

    /**
     * @var ?int $resultCode
     */
    #[JsonProperty('resultCode')]
    public ?int $resultCode;

    /**
     * @var ?string $resultText
     */
    #[JsonProperty('resultText')]
    public ?string $resultText;

    /**
     * Internal unique ID of customer owner of the stored method.
     *
     * Returns `0` if the method wasn't assigned to an existing customer or no customer was created."
     *
     * @var ?int $customerId
     */
    #[JsonProperty('customerId')]
    public ?int $customerId;

    /**
     * @var ?string $methodReferenceId
     */
    #[JsonProperty('methodReferenceId')]
    public ?string $methodReferenceId;

    /**
     * @param array{
     *   referenceId?: ?string,
     *   resultCode?: ?int,
     *   resultText?: ?string,
     *   customerId?: ?int,
     *   methodReferenceId?: ?string,
     * } $values
     */
    public function __construct(
        array $values = [],
    ) {
        $this->referenceId = $values['referenceId'] ?? null;
        $this->resultCode = $values['resultCode'] ?? null;
        $this->resultText = $values['resultText'] ?? null;
        $this->customerId = $values['customerId'] ?? null;
        $this->methodReferenceId = $values['methodReferenceId'] ?? null;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->toJson();
    }
}
