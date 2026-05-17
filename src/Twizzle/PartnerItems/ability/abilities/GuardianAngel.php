<?php

namespace Twizzle\PartnerItems\ability\abilities;

use pocketmine\player\Player;
use Twizzle\PartnerItems\ability\Ability;
use Twizzle\PartnerItems\Loader;

class GuardianAngel extends Ability {

    private Loader $plugin;

    public function __construct(Loader $plugin) {
        $this->plugin = $plugin;
        parent::__construct(
            "guardian_angel",
            "Guardian Angel",
            "Passive: Heals 4 hearts when health drops below 2 hearts. Consumed on use.",
            0
        );
    }

    public function execute(Player $player): bool {
        $session = $this->plugin->getSessionManager()->getSession($player);
        $session->set("guardian_angel_active", true);

        $player->sendMessage("§a- Guardian Angel equipped!");
        $player->sendMessage("§e- Will activate when health drops below 2 hearts");

        return true;
    }
}
