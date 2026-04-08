<?php

namespace Payabli\TokenStorage\Requests;

use Payabli\Core\Json\JsonSerializableType;
use Payabli\TokenStorage\Types\RequestTokenStorage;

class AddMethodRequest extends JsonSerializableType
{
    /**
     * @var ?bool $achValidation
     */
    public ?bool $achValidation;

    /**
     * @var ?bool $createAnonymous
     */
    public ?bool $createAnonymous;

    /**
     * @var ?bool $forceCustomerCreation
     */
    public ?bool $forceCustomerCreation;

    /**
     * @var ?bool $temporary
     */
    public ?bool $temporary;

    /**
     * @var ?string $idempotencyKey
     */
    public ?string $idempotencyKey;

    /**
     * @var RequestTokenStorage $body
     */
    public RequestTokenStorage $body;

    /**
     * @param array{
     *   body: RequestTokenStorage,
     *   achValidation?: ?bool,
     *   createAnonymous?: ?bool,
     *   forceCustomerCreation?: ?bool,
     *   temporary?: ?bool,
     *   idempotencyKey?: ?string,
     * } $values
     */
    public function __construct(
        array $values,
    ) {
        $this->achValidation = $values['achValidation'] ?? null;
        $this->createAnonymous = $values['createAnonymous'] ?? null;
        $this->forceCustomerCreation = $values['forceCustomerCreation'] ?? null;
        $this->temporary = $values['temporary'] ?? null;
        $this->idempotencyKey = $values['idempotencyKey'] ?? null;
        $this->body = $values['body'];
    }
}
