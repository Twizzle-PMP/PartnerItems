<?php

namespace Twizzle\PartnerItems\listener;

use pocketmine\event\Listener;
use pocketmine\event\player\PlayerItemUseEvent;
use pocketmine\item\VanillaItems;
use Twizzle\PartnerItems\Loader;

class ItemListener implements Listener {

    private Loader $plugin;

    public function __construct(Loader $plugin) {
        $this->plugin = $plugin;
    }

    public function onItemUse(PlayerItemUseEvent $event): void {
        $player = $event->getPlayer();
        $item = $event->getItem();
        $nbt = $item->getNamedTag();

        if (!$nbt->getTag("partner_id")) {
            return;
        }

        $id = $nbt->getString("partner_id");
        $ability = $this->plugin->getAbilityManager()->get($id);

        if ($ability === null) {
            return;
        }

        $event->cancel();

        if ($id === "guardian_angel") {
            $player->sendMessage("§cGuardian Angel is passive and activates automatically!");
            return;
        }

        $result = $this->plugin->getAbilityManager()->use($id, $player);

        if ($result) {
            $hand = $player->getInventory()->getItemInHand();
            if ($hand->getCount() > 1) {
                $hand->setCount($hand->getCount() - 1);
                $player->getInventory()->setItemInHand($hand);
            } else {
                $player->getInventory()->setItemInHand(VanillaItems::AIR());
            }
        }
    }
}