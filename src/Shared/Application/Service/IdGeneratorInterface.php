<?php

namespace Przper\Tribe\Shared\Application\Service;

use Przper\Tribe\Shared\Domain\Id;

interface IdGeneratorInterface
{
    public function generate(): Id;
}
