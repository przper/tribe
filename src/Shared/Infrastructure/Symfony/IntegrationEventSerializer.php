<?php

namespace Przper\Tribe\Shared\Infrastructure\Symfony;

use Przper\Tribe\Identity\AntiCorruption\Integration\Authentication\UserCreated;
use Przper\Tribe\Shared\AntiCorruption\IntegrationEventInterface;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Transport\Serialization\SerializerInterface;

class IntegrationEventSerializer implements SerializerInterface
{
    /**
     * @param array<string, mixed> $encodedEnvelope
     * @throws \Exception
     */
    public function decode(array $encodedEnvelope): Envelope
    {
        $body = json_decode($encodedEnvelope['body'], true);
        $eventName = $body['eventName'] ?? null;
        if (!$eventName) {
            throw new \Exception('Event name is not set!');
        }

        $integrationEvent = match ($eventName) {
            UserCreated::getEventName() => new UserCreated($body['userId'], $body['email'], $body['name']),
            default => null,
        };

        return new Envelope($integrationEvent);
    }

    /**
     * @return array<string, mixed>
     */
    public function encode(Envelope $envelope): array
    {
        $message = $envelope->getMessage();

        if (!$message instanceof IntegrationEventInterface) {
            $messageData = get_object_vars($message);
        } else {
            $messageData = [
                'version' => 1,
                'eventName' => $message->getEventName(),
            ];

            $reflection = new \ReflectionClass($message);
            foreach ($reflection->getProperties() as $property) {
                $property->setAccessible(true);
                $propertyName = $property->getName();
                if ($propertyName !== 'eventName') {
                    $messageData[$propertyName] = $property->getValue($message);
                }
            }
        }

        return [
            'body' => json_encode($messageData),
            'headers' => [
                'Content-Type' => 'application/json',
            ],
        ];
    }
}