<?php

namespace Payabli\TokenStorage\Requests;

use Payabli\Core\Json\JsonSerializableType;
use Payabli\TokenStorage\Types\RequestTokenStorage;

class UpdateMethodRequest extends JsonSerializableType
{
    /**
     * @var ?bool $achValidation
     */
    public ?bool $achValidation;

    /**
     * @var RequestTokenStorage $body
     */
    public RequestTokenStorage $body;

    /**
     * @param array{
     *   body: RequestTokenStorage,
     *   achValidation?: ?bool,
     * } $values
     */
    public function __construct(
        array $values,
    ) {
        $this->achValidation = $values['achValidation'] ?? null;
        $this->body = $values['body'];
    }
}
