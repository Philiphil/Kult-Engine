<?php

namespace KultEngine;

trait TimableTrait
{
    public ?\DateTime $createdAt = null;
    public ?\DateTime $modifiedAt = null;
    public ?\DateTime $deletedAt = null;
}