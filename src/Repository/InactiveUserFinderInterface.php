<?php

namespace App\Repository;

use App\Entity\User;

interface InactiveUserFinderInterface
{
    public function findInactive(int $id): ?User;
}
