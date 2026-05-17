<?php

namespace Twizzle\PartnerItems\manager;

use pocketmine\player\Player;
use Twizzle\PartnerItems\Loader;
use Twizzle\PartnerItems\session\PlayerSession;

class SessionManager {

    private Loader $plugin;
    private array $sessions = [];

    public function __construct(Loader $plugin) {
        $this->plugin = $plugin;
    }

    public function getSession(Player $player): PlayerSession {
        $name = strtolower($player->getName());

        if (!isset($this->sessions[$name])) {
            $this->sessions[$name] = new PlayerSession($player);
        }

        return $this->sessions[$name];
    }

    public function removeSession(Player $player): void {
        unset($this->sessions[strtolower($player->getName())]);
    }
}
