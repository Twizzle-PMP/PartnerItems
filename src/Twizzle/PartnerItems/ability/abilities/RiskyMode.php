<?php

namespace Twizzle\PartnerItems\ability\abilities;

use pocketmine\entity\effect\EffectInstance;
use pocketmine\entity\effect\VanillaEffects;
use pocketmine\player\Player;
use pocketmine\scheduler\ClosureTask;
use Twizzle\PartnerItems\ability\Ability;
use Twizzle\PartnerItems\Loader;

class RiskyMode extends Ability {

    private Loader $plugin;

    public function __construct(Loader $plugin) {
        $this->plugin = $plugin;
        parent::__construct(
            "risky_mode",
            "Risky Mode",
            "Grants Strength III for 6 seconds, then Weakness I for 4 seconds",
            140
        );
    }

    public function execute(Player $player): bool {
        $strength = new EffectInstance(VanillaEffects::STRENGTH(), 20 * 6, 2, false);
        $player->getEffects()->add($strength);

        $player->sendMessage("§a- You have used a Risky Mode!");
        $player->sendMessage("§e- You gained Strength III for 6 seconds");
        $player->sendMessage("§c- Warning: Weakness I will follow for 4 seconds");
        $player->sendMessage("§c- Countdown: " . $this->plugin->getConfigManager()->getCooldown("risky_mode") . "s");

        $this->plugin->getScheduler()->scheduleDelayedTask(new ClosureTask(function() use ($player): void {
            if ($player->isOnline()) {
                $weakness = new EffectInstance(VanillaEffects::WEAKNESS(), 20 * 4, 0, false);
                $player->getEffects()->add($weakness);
                $player->sendMessage("§c- Risky Mode weakness applied!");
            }
        }), 20 * 6);

        return true;
    }
}
