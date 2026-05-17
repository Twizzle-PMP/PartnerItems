<?php

namespace Twizzle\PartnerItems\ability\abilities;

use pocketmine\player\Player;
use Twizzle\PartnerItems\ability\Ability;
use Twizzle\PartnerItems\Loader;

class AntiTrap extends Ability {

    private Loader $plugin;

    public function __construct(Loader $plugin) {
        $this->plugin = $plugin;
        parent::__construct(
            "anti_trap",
            "Anti-Trap",
            "After 3 hits on an enemy, they cannot open fence gates, place blocks or open chests for 15 seconds",
            80
        );
    }

    public function execute(Player $player): bool {
        $player->sendMessage("§a- You have used an Anti-Trap!");
        $player->sendMessage("§e- Hit an enemy 3 times to trap them");
        $player->sendMessage("§c- Countdown: " . $this->plugin->getConfigManager()->getCooldown("anti_trap") . "s");

        $session = $this->plugin->getSessionManager()->getSession($player);
        $session->set("anti_trap_active", true);

        return true;
    }
}
