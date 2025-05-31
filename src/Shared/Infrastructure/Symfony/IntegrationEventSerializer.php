<?php

namespace Przper\Tribe\Shared\Infrastructure\Symfony;

use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Transport\Serialization\SerializerInterface;

class IntegrationEventSerializer implements SerializerInterface
{
    public function decode(array $encodedEnvelope): Envelope
    {
        $body = json_decode($encodedEnvelope['body'], true);
        if (!isset($body['eventName'])) {
            throw new \Exception('Event name is not set!');
        }

        return new Envelope(new class($body) { public function __construct(public array $body) {}});
    }

    public function encode(Envelope $envelope): array
    {
        return get_object_vars($envelope->getMessage());
    }
}