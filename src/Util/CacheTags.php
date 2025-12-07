<?php

namespace App\Util;

Enum CacheTags: string
{
    case USER = 'user_';

    case USER_DEVICE_SEARCH_KEY = 'user_device_search_';

    case USER_DEVICES_SEARCH_TAG = 'user_devices_';


    /**
     * Creates cache tag with a specific param
     */
    public function withId(int|array|string $param): string
    {
        return $this->value . (is_array($param) ? join("_", $param) : $param);
    }
}
