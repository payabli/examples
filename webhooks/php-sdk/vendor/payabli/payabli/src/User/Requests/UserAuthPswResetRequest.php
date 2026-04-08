<?php

namespace Payabli\User\Requests;

use Payabli\Core\Json\JsonSerializableType;
use Payabli\Core\Json\JsonProperty;

class UserAuthPswResetRequest extends JsonSerializableType
{
    /**
     * @var ?string $psw New User password
     */
    #[JsonProperty('psw')]
    public ?string $psw;

    /**
     * @param array{
     *   psw?: ?string,
     * } $values
     */
    public function __construct(
        array $values = [],
    ) {
        $this->psw = $values['psw'] ?? null;
    }
}
