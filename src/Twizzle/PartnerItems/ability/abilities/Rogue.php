<?php

namespace Twizzle\PartnerItems\ability\abilities;

use pocketmine\player\Player;
use Twizzle\PartnerItems\ability\Ability;
use Twizzle\PartnerItems\Loader;

class Rogue extends Ability {

    private Loader $plugin;

    public function __construct(Loader $plugin) {
        $this->plugin = $plugin;
        parent::__construct(
            "rogue",
            "Rogue",
            "Backstab: 2.5x damage and Blindness I for 3 seconds when hitting from behind",
            45
        );
    }

    public function execute(Player $player): bool {
        $player->sendMessage("§a- You have used a Rogue!");
        $player->sendMessage("§e- Backstab active for next hit");
        $player->sendMessage("§c- Countdown: " . $this->plugin->getConfigManager()->getCooldown("rogue") . "s");

        $session = $this->plugin->getSessionManager()->getSession($player);
        $session->set("rogue_active", true);

        return true;
    }
}
