<?php

namespace Twizzle\PartnerItems\ability\abilities;

use pocketmine\entity\effect\EffectInstance;
use pocketmine\entity\effect\VanillaEffects;
use pocketmine\player\Player;
use Twizzle\PartnerItems\ability\Ability;
use Twizzle\PartnerItems\Loader;

class Cactus extends Ability {

    private Loader $plugin;

    public function __construct(Loader $plugin) {
        $this->plugin = $plugin;
        parent::__construct(
            "cactus",
            "Cactus",
            "Grants Strength II, Regeneration III and Speed III for 8 seconds",
            130
        );
    }

    public function execute(Player $player): bool {
        $strength = new EffectInstance(VanillaEffects::STRENGTH(), 20 * 8, 1, false);
        $regen = new EffectInstance(VanillaEffects::REGENERATION(), 20 * 8, 2, false);
        $speed = new EffectInstance(VanillaEffects::SPEED(), 20 * 8, 2, false);

        $player->getEffects()->add($strength);
        $player->getEffects()->add($regen);
        $player->getEffects()->add($speed);

        $player->sendMessage("§a- You have used a Cactus!");
        $player->sendMessage("§e- You gained Strength II, Regeneration III and Speed III");
        $player->sendMessage("§c- Countdown: " . $this->plugin->getConfigManager()->getCooldown("cactus") . "s");

        return true;
    }
}
