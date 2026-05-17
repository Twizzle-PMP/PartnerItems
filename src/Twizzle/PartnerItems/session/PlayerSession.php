<?php

namespace Twizzle\PartnerItems\session;

use pocketmine\player\Player;

class PlayerSession {

    private Player $player;
    private array $cache = [];
    private ?Player $lastHitBy = null;
    private int $lastHitTime = 0;
    private array $pearlLocations = [];
    private int $antiTrapHits = 0;
    private ?Player $antiTrapTarget = null;
    private int $stormbreakerHits = 0;
    private ?Player $stormbreakerTarget = null;

    public function __construct(Player $player) {
        $this->player = $player;
    }

    public function getPlayer(): Player {
        return $this->player;
    }

    public function set(string $key, mixed $value): void {
        $this->cache[$key] = $value;
    }

    public function get(string $key, mixed $default = null): mixed {
        return $this->cache[$key] ?? $default;
    }

    public function remove(string $key): void {
        unset($this->cache[$key]);
    }

    public function clear(): void {
        $this->cache = [];
    }

    public function setLastHitBy(?Player $player): void {
        $this->lastHitBy = $player;
        $this->lastHitTime = time();
    }

    public function getLastHitBy(): ?Player {
        if ($this->lastHitBy !== null && (time() - $this->lastHitTime) > 10) {
            $this->lastHitBy = null;
        }
        return $this->lastHitBy;
    }

    public function getLastHitTime(): int {
        return $this->lastHitTime;
    }

    public function addPearlLocation(array $loc): void {
        $this->pearlLocations[] = [
            "x" => $loc["x"],
            "y" => $loc["y"],
            "z" => $loc["z"],
            "world" => $loc["world"],
            "time" => time()
        ];

        foreach ($this->pearlLocations as $i => $data) {
            if ((time() - $data["time"]) > 20) {
                unset($this->pearlLocations[$i]);
            }
        }

        $this->pearlLocations = array_values($this->pearlLocations);
    }

    public function getLastPearlLocation(): ?array {
        if (empty($this->pearlLocations)) {
            return null;
        }

        $last = end($this->pearlLocations);
        if ((time() - $last["time"]) > 20) {
            return null;
        }

        return $last;
    }

    public function addAntiTrapHit(Player $target): void {
        if ($this->antiTrapTarget !== null && $this->antiTrapTarget->getName() !== $target->getName()) {
            $this->antiTrapHits = 0;
        }

        $this->antiTrapTarget = $target;
        $this->antiTrapHits++;

        if ($this->antiTrapHits >= 3) {
            $this->antiTrapHits = 0;
        }
    }

    public function getAntiTrapHits(): int {
        return $this->antiTrapHits;
    }

    public function getAntiTrapTarget(): ?Player {
        return $this->antiTrapTarget;
    }

    public function addStormbreakerHit(Player $target): void {
        if ($this->stormbreakerTarget !== null && $this->stormbreakerTarget->getName() !== $target->getName()) {
            $this->stormbreakerHits = 0;
        }

        $this->stormbreakerTarget = $target;
        $this->stormbreakerHits++;

        if ($this->stormbreakerHits >= 3) {
            $this->stormbreakerHits = 0;
        }
    }

    public function getStormbreakerHits(): int {
        return $this->stormbreakerHits;
    }

    public function getStormbreakerTarget(): ?Player {
        return $this->stormbreakerTarget;
    }
}
