<?php

/**
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade Zentlix to newer
 * versions in the future. If you wish to customize Zentlix for your
 * needs please refer to https://docs.zentlix.io for more information.
 */

declare(strict_types=1);

namespace Zentlix\PageBundle\Application\Query\Page;

use Zentlix\MainBundle\Infrastructure\Share\Bus\QueryInterface;

class PageQuery implements QueryInterface
{
    private string $code;
    private string $siteId;

    public function __construct(string $code, string $siteId)
    {
        $this->code = $code;
        $this->siteId = $siteId;
    }

    public function getCode(): string
    {
        return $this->code;
    }

    public function getSiteId(): string
    {
        return $this->siteId;
    }
}