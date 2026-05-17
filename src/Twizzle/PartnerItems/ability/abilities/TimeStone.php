<?php

namespace Twizzle\PartnerItems\ability\abilities;

use pocketmine\player\Player;
use Twizzle\PartnerItems\ability\Ability;
use Twizzle\PartnerItems\Loader;

class TimeStone extends Ability {

    private Loader $plugin;

    public function __construct(Loader $plugin) {
        $this->plugin = $plugin;
        parent::__construct(
            "time_stone",
            "Time Stone",
            "Clears all your ability cooldowns instantly",
            180
        );
    }

    public function execute(Player $player): bool {
        $cd = $this->plugin->getCooldownManager();
        $cd->clearAllCooldowns($player);

        $player->sendMessage("§a- You have used a Time Stone!");
        $player->sendMessage("§e- All ability cooldowns cleared!");
        $player->sendMessage("§c- Countdown: " . $this->plugin->getConfigManager()->getCooldown("time_stone") . "s");

        return true;
    }
}
