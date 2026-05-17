<?php

namespace Twizzle\PartnerItems\manager;

use pocketmine\player\Player;
use Twizzle\PartnerItems\Loader;

class CooldownManager {

    private Loader $plugin;
    private array $data = [];

    public function __construct(Loader $plugin) {
        $this->plugin = $plugin;
    }

    public function setCooldown(Player $player, string $id, int $seconds): void {
        $this->data[strtolower($player->getName())][$id] = time() + $seconds;
    }

    public function getCooldown(Player $player, string $id): int {
        $name = strtolower($player->getName());

        if (!isset($this->data[$name][$id])) {
            return 0;
        }

        $left = $this->data[$name][$id] - time();
        return max(0, $left);
    }

    public function hasCooldown(Player $player, string $id): bool {
        return $this->getCooldown($player, $id) > 0;
    }

    public function clearCooldown(Player $player, string $id): void {
        unset($this->data[strtolower($player->getName())][$id]);
    }

    public function clearAllCooldowns(Player $player): void {
        $this->data[strtolower($player->getName())] = [];
    }
}
