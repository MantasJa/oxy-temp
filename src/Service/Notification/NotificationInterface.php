<?php

namespace App\Service\Notification;

use App\Entity\User;
use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;

#[AutoconfigureTag('app.notification')]
interface NotificationInterface
{
    public function get(User $user): ?array;
}
