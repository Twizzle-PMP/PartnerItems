<?php

namespace Twizzle\PartnerItems\ability\abilities;

use pocketmine\player\Player;
use Twizzle\PartnerItems\ability\Ability;
use Twizzle\PartnerItems\Loader;

class Ninja extends Ability {

    private Loader $plugin;

    public function __construct(Loader $plugin) {
        $this->plugin = $plugin;
        parent::__construct(
            "ninja",
            "Ninja",
            "Teleports behind the last player who hit you within 10 seconds",
            90
        );
    }

    public function execute(Player $player): bool {
        $session = $this->plugin->getSessionManager()->getSession($player);
        $lastHitBy = $session->getLastHitBy();

        if ($lastHitBy === null || !$lastHitBy->isOnline()) {
            $player->sendMessage("§cNobody has hit you recently!");
            return false;
        }

        $target = $lastHitBy;
        $direction = $target->getDirectionVector();
        $behind = $target->getPosition()->subtractVector($direction->multiply(2));

        $player->teleport($behind);

        $player->sendMessage("§a- You have used a Ninja!");
        $player->sendMessage("§e- Teleported behind " . $target->getName());
        $player->sendMessage("§c- Countdown: " . $this->plugin->getConfigManager()->getCooldown("ninja") . "s");

        return true;
    }
}
