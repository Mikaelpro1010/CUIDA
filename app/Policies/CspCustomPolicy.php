<?php

namespace App\Policies;

use Spatie\Csp\Directive;
use Spatie\Csp\Keyword;
use Spatie\Csp\Policies\Basic;
use Spatie\Csp\Scheme;

class CspCustomPolicy extends Basic
{
    public function configure()
    {
        parent::configure();
        $this
            ->addDirective(Directive::IMG, Scheme::DATA)
            ->addDirective(Directive::FRAME_ANCESTORS, Keyword::NONE)
            ->addDirective(Directive::STYLE, Keyword::UNSAFE_INLINE)
            ->addDirective(Directive::STYLE, Keyword::STRICT_DYNAMIC)
            ->addDirective(Directive::SCRIPT, Keyword::UNSAFE_INLINE)
            ->addDirective(Directive::SCRIPT, Keyword::STRICT_DYNAMIC)
            //
        ;
    }
}
