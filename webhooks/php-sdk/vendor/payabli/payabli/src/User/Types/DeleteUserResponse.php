<?php

namespace Payabli\User\Types;

use Payabli\Core\Json\JsonSerializableType;
use Payabli\Core\Json\JsonProperty;

class DeleteUserResponse extends JsonSerializableType
{
    /**
     * @var string $responseText
     */
    #[JsonProperty('responseText')]
    public string $responseText;

    /**
     * @param array{
     *   responseText: string,
     * } $values
     */
    public function __construct(
        array $values,
    ) {
        $this->responseText = $values['responseText'];
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->toJson();
    }
}
