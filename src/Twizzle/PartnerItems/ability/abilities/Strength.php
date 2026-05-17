<?php

namespace Twizzle\PartnerItems\ability\abilities;

use pocketmine\entity\effect\EffectInstance;
use pocketmine\entity\effect\VanillaEffects;
use pocketmine\player\Player;
use Twizzle\PartnerItems\ability\Ability;
use Twizzle\PartnerItems\Loader;

class Strength extends Ability {

    private Loader $plugin;

    public function __construct(Loader $plugin) {
        $this->plugin = $plugin;
        parent::__construct(
            "strength",
            "Strength",
            "Grants Strength II for 8 seconds",
            120
        );
    }

    public function execute(Player $player): bool {
        $effect = new EffectInstance(VanillaEffects::STRENGTH(), 20 * 8, 1, false);
        $player->getEffects()->add($effect);

        $player->sendMessage("§a- You have used a Strength!");
        $player->sendMessage("§e- You gained Strength II for 8 seconds");
        $player->sendMessage("§c- Countdown: " . $this->plugin->getConfigManager()->getCooldown("strength") . "s");

        return true;
    }
}
