<?php

namespace Twizzle\PartnerItems\ability\abilities;

use pocketmine\player\Player;
use Twizzle\PartnerItems\ability\Ability;
use Twizzle\PartnerItems\Loader;

class Stormbreaker extends Ability {

    private Loader $plugin;

    public function __construct(Loader $plugin) {
        $this->plugin = $plugin;
        parent::__construct(
            "stormbreaker",
            "Stormbreaker",
            "After 3 hits with golden axe, removes enemy helmet after 4 second warning",
            85
        );
    }

    public function execute(Player $player): bool {
        $player->sendMessage("§a- You have used a Stormbreaker!");
        $player->sendMessage("§e- Hit an enemy 3 times with golden axe");
        $player->sendMessage("§c- Countdown: " . $this->plugin->getConfigManager()->getCooldown("stormbreaker") . "s");

        return true;
    }
}
