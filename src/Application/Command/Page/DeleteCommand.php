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

use Symfony\Component\Validator\Constraints;
use Zentlix\MainBundle\Infrastructure\Share\Bus\DeleteCommandInterface;
use Zentlix\MainBundle\Infrastructure\Share\Bus\CommandInterface;
use Zentlix\PageBundle\Domain\Page\Entity\Page;

class DeleteCommand implements DeleteCommandInterface, CommandInterface
{
    /** @Constraints\NotBlank() */
    public Page $page;

    public function __construct(Page $page)
    {
        $this->page = $page;
    }
}