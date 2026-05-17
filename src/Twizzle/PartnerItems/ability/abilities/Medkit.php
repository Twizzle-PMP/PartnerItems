<?php

namespace Twizzle\PartnerItems\ability\abilities;

use pocketmine\entity\effect\EffectInstance;
use pocketmine\entity\effect\VanillaEffects;
use pocketmine\player\Player;
use Twizzle\PartnerItems\ability\Ability;
use Twizzle\PartnerItems\Loader;

class Medkit extends Ability {

    private Loader $plugin;

    public function __construct(Loader $plugin) {
        $this->plugin = $plugin;
        parent::__construct(
            "medkit",
            "Medkit",
            "Grants Regeneration IV, Absorption VI and Resistance III for 8 seconds",
            160
        );
    }

    public function execute(Player $player): bool {
        $regen = new EffectInstance(VanillaEffects::REGENERATION(), 20 * 8, 3, false);
        $absorption = new EffectInstance(VanillaEffects::ABSORPTION(), 20 * 8, 5, false);
        $resistance = new EffectInstance(VanillaEffects::RESISTANCE(), 20 * 8, 2, false);

        $player->getEffects()->add($regen);
        $player->getEffects()->add($absorption);
        $player->getEffects()->add($resistance);

        $player->sendMessage("§a- You have used a Medkit!");
        $player->sendMessage("§e- You gained Regeneration IV, Absorption VI and Resistance III");
        $player->sendMessage("§c- Countdown: " . $this->plugin->getConfigManager()->getCooldown("medkit") . "s");

        return true;
    }
}
