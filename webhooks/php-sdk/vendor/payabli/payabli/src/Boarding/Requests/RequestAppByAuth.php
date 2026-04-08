<?php

namespace Payabli\Boarding\Requests;

use Payabli\Core\Json\JsonSerializableType;
use Payabli\Core\Json\JsonProperty;

class RequestAppByAuth extends JsonSerializableType
{
    /**
     * @var ?string $email The email address the applicant used to save the application.
     */
    #[JsonProperty('email')]
    public ?string $email;

    /**
     * @var ?string $referenceId The referenceId is sent to the applicant via email when they save the application.
     */
    #[JsonProperty('referenceId')]
    public ?string $referenceId;

    /**
     * @param array{
     *   email?: ?string,
     *   referenceId?: ?string,
     * } $values
     */
    public function __construct(
        array $values = [],
    ) {
        $this->email = $values['email'] ?? null;
        $this->referenceId = $values['referenceId'] ?? null;
    }
}
