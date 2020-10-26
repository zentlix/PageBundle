<?php

/**
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade Zentlix to newer
 * versions in the future. If you wish to customize Zentlix for your
 * needs please refer to https://docs.zentlix.io for more information.
 */

declare(strict_types=1);

namespace Zentlix\PageBundle\Application\Command\Page;

use Zentlix\MainBundle\Infrastructure\Share\Bus\CreateCommandInterface;
use Zentlix\MainBundle\Domain\Shared\ValueObject\Meta;

class CreateCommand extends Command implements CreateCommandInterface
{
    public function __construct()
    {
        $this->meta = new Meta();
    }
}