<?php

namespace Payabli\Types;

use Payabli\Core\Json\JsonSerializableType;
use Exception;
use Payabli\Core\Json\JsonDecoder;

/**
 * Request body for the push paylink operation.
 */
class PushPayLinkRequest extends JsonSerializableType
{
    /**
     * @var (
     *    'email'
     *   |'sms'
     *   |'_unknown'
     * ) $channel
     */
    public readonly string $channel;

    /**
     * @var (
     *    PushPayLinkRequestEmail
     *   |PushPayLinkRequestSms
     *   |mixed
     * ) $value
     */
    public readonly mixed $value;

    /**
     * @param array{
     *   channel: (
     *    'email'
     *   |'sms'
     *   |'_unknown'
     * ),
     *   value: (
     *    PushPayLinkRequestEmail
     *   |PushPayLinkRequestSms
     *   |mixed
     * ),
     * } $values
     */
    private function __construct(
        array $values,
    ) {
        $this->channel = $values['channel'];
        $this->value = $values['value'];
    }

    /**
     * @param PushPayLinkRequestEmail $email
     * @return PushPayLinkRequest
     */
    public static function email(PushPayLinkRequestEmail $email): PushPayLinkRequest
    {
        return new PushPayLinkRequest([
            'channel' => 'email',
            'value' => $email,
        ]);
    }

    /**
     * @param PushPayLinkRequestSms $sms
     * @return PushPayLinkRequest
     */
    public static function sms(PushPayLinkRequestSms $sms): PushPayLinkRequest
    {
        return new PushPayLinkRequest([
            'channel' => 'sms',
            'value' => $sms,
        ]);
    }

    /**
     * @return bool
     */
    public function isEmail(): bool
    {
        return $this->value instanceof PushPayLinkRequestEmail && $this->channel === 'email';
    }

    /**
     * @return PushPayLinkRequestEmail
     */
    public function asEmail(): PushPayLinkRequestEmail
    {
        if (!($this->value instanceof PushPayLinkRequestEmail && $this->channel === 'email')) {
            throw new Exception(
                "Expected email; got " . $this->channel . " with value of type " . get_debug_type($this->value),
            );
        }

        return $this->value;
    }

    /**
     * @return bool
     */
    public function isSms(): bool
    {
        return $this->value instanceof PushPayLinkRequestSms && $this->channel === 'sms';
    }

    /**
     * @return PushPayLinkRequestSms
     */
    public function asSms(): PushPayLinkRequestSms
    {
        if (!($this->value instanceof PushPayLinkRequestSms && $this->channel === 'sms')) {
            throw new Exception(
                "Expected sms; got " . $this->channel . " with value of type " . get_debug_type($this->value),
            );
        }

        return $this->value;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->toJson();
    }

    /**
     * @return array<mixed>
     */
    public function jsonSerialize(): array
    {
        $result = [];
        $result['channel'] = $this->channel;

        $base = parent::jsonSerialize();
        $result = array_merge($base, $result);

        switch ($this->channel) {
            case 'email':
                $value = $this->asEmail()->jsonSerialize();
                $result = array_merge($value, $result);
                break;
            case 'sms':
                $value = $this->asSms()->jsonSerialize();
                $result = array_merge($value, $result);
                break;
            case '_unknown':
            default:
                if (is_null($this->value)) {
                    break;
                }
                if ($this->value instanceof JsonSerializableType) {
                    $value = $this->value->jsonSerialize();
                    $result = array_merge($value, $result);
                } elseif (is_array($this->value)) {
                    $result = array_merge($this->value, $result);
                }
        }

        return $result;
    }

    /**
     * @param string $json
     */
    public static function fromJson(string $json): static
    {
        $decodedJson = JsonDecoder::decode($json);
        if (!is_array($decodedJson)) {
            throw new Exception("Unexpected non-array decoded type: " . gettype($decodedJson));
        }
        return self::jsonDeserialize($decodedJson);
    }

    /**
     * @param array<string, mixed> $data
     */
    public static function jsonDeserialize(array $data): static
    {
        $args = [];
        if (!array_key_exists('channel', $data)) {
            throw new Exception(
                "JSON data is missing property 'channel'",
            );
        }
        $channel = $data['channel'];
        if (!(is_string($channel))) {
            throw new Exception(
                "Expected property 'channel' in JSON data to be string, instead received " . get_debug_type($data['channel']),
            );
        }

        $args['channel'] = $channel;
        switch ($channel) {
            case 'email':
                $args['value'] = PushPayLinkRequestEmail::jsonDeserialize($data);
                break;
            case 'sms':
                $args['value'] = PushPayLinkRequestSms::jsonDeserialize($data);
                break;
            case '_unknown':
            default:
                $args['channel'] = '_unknown';
                $args['value'] = $data;
        }

        // @phpstan-ignore-next-line
        return new static($args);
    }
}
