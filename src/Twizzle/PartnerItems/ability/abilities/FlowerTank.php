<?php

namespace Twizzle\PartnerItems\ability\abilities;

use pocketmine\entity\effect\EffectInstance;
use pocketmine\entity\effect\VanillaEffects;
use pocketmine\player\Player;
use Twizzle\PartnerItems\ability\Ability;
use Twizzle\PartnerItems\Loader;

class FlowerTank extends Ability {

    private Loader $plugin;

    public function __construct(Loader $plugin) {
        $this->plugin = $plugin;
        parent::__construct(
            "flower_tank",
            "Flower Tank",
            "Grants Regeneration IV and Resistance III for 10 seconds",
            120
        );
    }

    public function execute(Player $player): bool {
        $regen = new EffectInstance(VanillaEffects::REGENERATION(), 20 * 10, 3, false);
        $resistance = new EffectInstance(VanillaEffects::RESISTANCE(), 20 * 10, 2, false);

        $player->getEffects()->add($regen);
        $player->getEffects()->add($resistance);

        $player->sendMessage("§a- You have used a Flower Tank!");
        $player->sendMessage("§e- You gained Regeneration IV and Resistance III");
        $player->sendMessage("§c- Countdown: " . $this->plugin->getConfigManager()->getCooldown("flower_tank") . "s");

        return true;
    }
}
