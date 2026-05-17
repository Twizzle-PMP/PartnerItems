<?php

namespace Twizzle\PartnerItems\ability\abilities;

use pocketmine\entity\effect\EffectInstance;
use pocketmine\entity\effect\VanillaEffects;
use pocketmine\player\Player;
use Twizzle\PartnerItems\ability\Ability;
use Twizzle\PartnerItems\Loader;

class Resistance extends Ability {

    private Loader $plugin;

    public function __construct(Loader $plugin) {
        $this->plugin = $plugin;
        parent::__construct(
            "resistance",
            "Resistance",
            "Grants Resistance III for 6 seconds",
            100
        );
    }

    public function execute(Player $player): bool {
        $effect = new EffectInstance(VanillaEffects::RESISTANCE(), 20 * 6, 2, false);
        $player->getEffects()->add($effect);

        $player->sendMessage("§a- You have used a Resistance!");
        $player->sendMessage("§e- You gained Resistance III for 6 seconds");
        $player->sendMessage("§c- Countdown: " . $this->plugin->getConfigManager()->getCooldown("resistance") . "s");

        return true;
    }
}
