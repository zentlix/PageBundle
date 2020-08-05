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

use Symfony\Component\HttpFoundation\Request;
use Zentlix\MainBundle\Application\Command\CreateCommandInterface;
use Zentlix\MainBundle\Domain\Shared\ValueObject\Meta;

class CreateCommand extends Command implements CreateCommandInterface
{
    public function __construct(Request $request = null)
    {
        $this->meta = new Meta();

        if($request) {
            $main = $request->request->get('main');
            $content = $request->request->get('content');
            $this->title = $main['title'] ?? null;
            $this->content = $content['content'] ?? null;
            $this->active = $main['active'] ?? true;
            $this->code = $main['code'] ?? null;
            $this->sort = isset($main['sort']) ? (int) $main['sort'] : 1;
            $this->site = isset($main['site']) ? (int) $main['site'] : null;
            $this->setMeta($main['meta_title'] ?? null, $main['meta_description'] ?? null, $main['meta_keywords'] ?? null);
        }
    }
}