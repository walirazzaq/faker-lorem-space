<?php

namespace Walirazzaq\FakerLoremSpace;

use Faker\Provider\Base;

class FakerLoremSpace extends Base
{
    public static function loremSpace(
        int $width = 640,
        int $height = 480,
        $category = null,
    ): LoremSpace {
        return new LoremSpace($width, $height, $category);
    }
}
