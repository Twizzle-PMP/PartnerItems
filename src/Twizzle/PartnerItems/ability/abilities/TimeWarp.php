<?php

namespace Twizzle\PartnerItems\ability\abilities;

use pocketmine\player\Player;
use pocketmine\world\Position;
use Twizzle\PartnerItems\ability\Ability;
use Twizzle\PartnerItems\Loader;

class TimeWarp extends Ability {

    private Loader $plugin;

    public function __construct(Loader $plugin) {
        $this->plugin = $plugin;
        parent::__construct(
            "time_warp",
            "Time Warp",
            "Teleports you to your last thrown ender pearl within 20 seconds",
            75
        );
    }

    public function execute(Player $player): bool {
        $session = $this->plugin->getSessionManager()->getSession($player);
        $loc = $session->getLastPearlLocation();

        if ($loc === null) {
            $player->sendMessage("§cYou haven't thrown a pearl recently!");
            return false;
        }

        $world = $this->plugin->getServer()->getWorldManager()->getWorldByName($loc["world"]);
        if ($world === null) {
            $player->sendMessage("§cWorld not found!");
            return false;
        }

        $player->teleport(new Position($loc["x"], $loc["y"], $loc["z"], $world));

        $player->sendMessage("§a- You have used a Time Warp!");
        $player->sendMessage("§e- Teleported to your last pearl location");
        $player->sendMessage("§c- Countdown: " . $this->plugin->getConfigManager()->getCooldown("time_warp") . "s");

        return true;
    }
}
