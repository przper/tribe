<?php

namespace Przper\Tribe\Shared\AntiCorruption;

/**
 * @template T of IntegrationEventInterface
 */
abstract class IntegrationEventFactory
{
    /** @return string */
    abstract public function getExternalEventName(): string;

    /**
     * @param array<string, mixed> $data
     * @return T
     */
    abstract protected function buildEvent(array $data): IntegrationEventInterface;

    /**
     * @param array<string, mixed> $data
     * @throws NotSupportedIntegrationEventException
     * @return T
     */
    public function createFromArray(array $data): IntegrationEventInterface
    {
        if ($data['eventName'] !== $this->getExternalEventName()) {
            throw new NotSupportedIntegrationEventException();
        }

        $event = $this->buildEvent($data);

        if ($data['version'] !== $event->getVersion()) {
            throw new \InvalidArgumentException('Invalid event version');
        }

        return $event;
    }
}
