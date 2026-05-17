<?php

namespace Twizzle\PartnerItems\ability\abilities;

use pocketmine\player\Player;
use Twizzle\PartnerItems\ability\Ability;
use Twizzle\PartnerItems\Loader;

class WebShooter extends Ability {

    private Loader $plugin;

    public function __construct(Loader $plugin) {
        $this->plugin = $plugin;
        parent::__construct(
            "web_shooter",
            "Web Shooter",
            "Shoots a web that traps enemies in a 3x3 area with Slowness III for 4 seconds",
            70
        );
    }

    public function execute(Player $player): bool {
        $session = $this->plugin->getSessionManager()->getSession($player);
        $session->set("web_shooter_active", true);

        $player->sendMessage("§a- You have used a Web Shooter!");
        $player->sendMessage("§e- Throw to create a web trap");
        $player->sendMessage("§c- Countdown: " . $this->plugin->getConfigManager()->getCooldown("web_shooter") . "s");

        return true;
    }
}
