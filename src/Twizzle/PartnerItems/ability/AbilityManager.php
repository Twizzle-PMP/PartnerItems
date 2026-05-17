<?php

namespace Twizzle\PartnerItems\ability;

use pocketmine\player\Player;
use Twizzle\PartnerItems\Loader;

class AbilityManager {

    private Loader $plugin;
    private array $abilities = [];

    public function __construct(Loader $plugin) {
        $this->plugin = $plugin;
    }

    public function register(Ability $ability): void {
        $this->abilities[$ability->getId()] = $ability;
    }

    public function get(string $id): ?Ability {
        return $this->abilities[$id] ?? null;
    }

    public function getAll(): array {
        return $this->abilities;
    }

    public function use(string $id, Player $player): bool {
        $ability = $this->get($id);
        if ($ability === null) {
            return false;
        }

        $cd = $this->plugin->getCooldownManager();
        $cfg = $this->plugin->getConfigManager();

        if ($cd->hasCooldown($player, "global")) {
            $left = $cd->getCooldown($player, "global");
            $player->sendMessage($cfg->getPrefix() . $cfg->getMessage("global_cooldown", ["seconds" => $left]));
            return false;
        }

        if ($cd->hasCooldown($player, $id)) {
            $left = $cd->getCooldown($player, $id);
            $player->sendMessage($cfg->getPrefix() . $cfg->getMessage("cooldown", ["seconds" => $left]));
            return false;
        }

        $result = $ability->execute($player);

        if ($result) {
            $seconds = $cfg->getCooldown($id);
            if ($seconds < 1) {
                $seconds = $ability->getDefaultCooldown();
            }
            $cd->setCooldown($player, $id, $seconds);
            $cd->setCooldown($player, "global", $cfg->getCooldown("global"));
        } else {
            $player->sendMessage($cfg->getPrefix() . $cfg->getMessage("ability_failed"));
        }

        return $result;
    }
}
