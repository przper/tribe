<?php

namespace Przper\Tribe\WorkedTime\Ports\Controller;

use Przper\Tribe\WorkedTime\Infrastructure\PolcodeLinkWorkedTimeRetriever;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class PolcodeTimeController extends AbstractController
{
    public function __construct(
        private readonly PolcodeLinkWorkedTimeRetriever $timeRetriever,
    ) {}

    #[Route('/worked-time/polcode', name: 'worked_time.polcode')]
    public function __invoke(): Response
    {
        $start = new \DateTimeImmutable('first day of this month');
        $end = new \DateTimeImmutable('last day of this month');

        $workingMonth = $this->timeRetriever->retrieve($start, $end);

        $timeDifference = $workingMonth->getTotalWorkedTimeDuration()->difference($workingMonth->getExpectedWorkedTimeDuration());

        $message = $workingMonth->getTotalWorkedTimeDuration()->isGreaterThan($workingMonth->getExpectedWorkedTimeDuration())
            ? "Przemek is $timeDifference ahead"
            : "Przemek is $timeDifference behind"
        ;
        return new Response($message);
    }
}
