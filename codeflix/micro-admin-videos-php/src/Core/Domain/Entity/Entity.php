<?php

namespace Core\Domain\Entity;

use Core\Domain\Notification\Notification;

abstract class Entity
{
    public Notification $notification;

    public function __construct()
    {
        $this->notification = new Notification();
    }
}
