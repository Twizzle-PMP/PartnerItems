<?php

namespace Twizzle\PartnerItems\listener;

use pocketmine\event\entity\ProjectileLaunchEvent;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerItemUseEvent;
use pocketmine\item\EnderPearl;
use pocketmine\player\Player;
use Twizzle\PartnerItems\Loader;

class PearlListener implements Listener {

    private Loader $plugin;

    public function __construct(Loader $plugin) {
        $this->plugin = $plugin;
    }

    public function onProjectileLaunch(ProjectileLaunchEvent $event): void {
        $projectile = $event->getEntity();
        $owner = $projectile->getOwningEntity();

        if (!$owner instanceof Player) {
            return;
        }

        $session = $this->plugin->getSessionManager()->getSession($owner);
        $session->addPearlLocation([
            "x" => $owner->getPosition()->getX(),
            "y" => $owner->getPosition()->getY(),
            "z" => $owner->getPosition()->getZ(),
            "world" => $owner->getWorld()->getFolderName()
        ]);
    }

    public function onItemUse(PlayerItemUseEvent $event): void {
        $player = $event->getPlayer();
        $item = $event->getItem();

        if (!$item instanceof EnderPearl) {
            return;
        }

        $session = $this->plugin->getSessionManager()->getSession($player);
        $session->addPearlLocation([
            "x" => $player->getPosition()->getX(),
            "y" => $player->getPosition()->getY(),
            "z" => $player->getPosition()->getZ(),
            "world" => $player->getWorld()->getFolderName()
        ]);
    }
}
