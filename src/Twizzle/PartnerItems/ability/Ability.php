<?php

namespace Twizzle\PartnerItems\ability;

use pocketmine\player\Player;

abstract class Ability {

    protected string $id;
    protected string $displayName;
    protected string $description;
    protected int $defaultCooldown;

    public function __construct(string $id, string $displayName, string $description, int $defaultCooldown) {
        $this->id = $id;
        $this->displayName = $displayName;
        $this->description = $description;
        $this->defaultCooldown = $defaultCooldown;
    }

    public function getId(): string {
        return $this->id;
    }

    public function getDisplayName(): string {
        return $this->displayName;
    }

    public function getDescription(): string {
        return $this->description;
    }

    public function getDefaultCooldown(): int {
        return $this->defaultCooldown;
    }

    abstract public function execute(Player $player): bool;
}
