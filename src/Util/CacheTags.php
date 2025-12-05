<?php

namespace App\Util;

Enum CacheTags: string
{
    case USER = 'user_';

    /**
     * Creates cache tag with a specific id
     *
     * @param int $id
     * @return string
     */
    public function withId(int $id): string
    {
        return $this->value . $id;
    }
}
