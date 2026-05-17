<?php

namespace Twizzle\PartnerItems\ability\abilities;

use pocketmine\entity\effect\EffectInstance;
use pocketmine\entity\effect\VanillaEffects;
use pocketmine\player\Player;
use Twizzle\PartnerItems\ability\Ability;
use Twizzle\PartnerItems\Loader;

class Tank extends Ability {

    private Loader $plugin;

    public function __construct(Loader $plugin) {
        $this->plugin = $plugin;
        parent::__construct(
            "tank",
            "Tank",
            "Grants Absorption IV for 30 seconds",
            150
        );
    }

    public function execute(Player $player): bool {
        $effect = new EffectInstance(VanillaEffects::ABSORPTION(), 20 * 30, 3, false);
        $player->getEffects()->add($effect);

        $player->sendMessage("§a- You have used a Tank!");
        $player->sendMessage("§e- You gained Absorption IV for 30 seconds");
        $player->sendMessage("§c- Countdown: " . $this->plugin->getConfigManager()->getCooldown("tank") . "s");

        return true;
    }
}
