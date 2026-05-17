<?php

namespace Twizzle\PartnerItems\ability\abilities;

use pocketmine\player\Player;
use Twizzle\PartnerItems\ability\Ability;
use Twizzle\PartnerItems\Loader;

class Switcher extends Ability {

    private Loader $plugin;

    public function __construct(Loader $plugin) {
        $this->plugin = $plugin;
        parent::__construct(
            "switcher",
            "Switcher",
            "Throw a snowball to swap positions with your enemy",
            65
        );
    }

    public function execute(Player $player): bool {
        $session = $this->plugin->getSessionManager()->getSession($player);
        $session->set("switcher_active", true);

        $player->sendMessage("§a- You have used a Switcher!");
        $player->sendMessage("§e- Throw the snowball to swap positions");
        $player->sendMessage("§c- Countdown: " . $this->plugin->getConfigManager()->getCooldown("switcher") . "s");

        return true;
    }
}
