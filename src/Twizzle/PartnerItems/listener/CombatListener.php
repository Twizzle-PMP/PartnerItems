<?php

namespace Twizzle\PartnerItems\listener;

use pocketmine\event\block\BlockPlaceEvent;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\item\GoldenAxe;
use pocketmine\player\Player;
use pocketmine\scheduler\ClosureTask;
use Twizzle\PartnerItems\Loader;

class CombatListener implements Listener {

    private Loader $plugin;

    public function __construct(Loader $plugin) {
        $this->plugin = $plugin;
    }

    public function onDamage(EntityDamageByEntityEvent $event): void {
        $damager = $event->getDamager();
        $entity = $event->getEntity();

        if (!$damager instanceof Player || !$entity instanceof Player) {
            return;
        }

        $session = $this->plugin->getSessionManager()->getSession($damager);
        $targetSession = $this->plugin->getSessionManager()->getSession($entity);

        $targetSession->setLastHitBy($damager);

        $item = $damager->getInventory()->getItemInHand();

        if ($item instanceof GoldenAxe) {
            $session->addStormbreakerHit($entity);

            if ($session->getStormbreakerHits() >= 3) {
                $entity->sendMessage("§cYour helmet will be removed in §c4 §eseconds!");

                $this->plugin->getScheduler()->scheduleDelayedTask(new ClosureTask(function() use ($entity, $damager): void {
                    if (!$entity->isOnline()) {
                        return;
                    }

                    $helmet = $entity->getArmorInventory()->getHelmet();
                    if (!$helmet->isNull()) {
                        $entity->getArmorInventory()->setHelmet(\pocketmine\item\VanillaItems::AIR());
                        $entity->sendMessage("§cYour helmet was removed by Stormbreaker!");
                        $entity->getWorld()->dropItem($entity->getPosition(), $helmet);
                    }
                }), 20 * 4);
            }
        }

        if ($session->get("rogue_active")) {
            $direction = $entity->getDirectionVector();
            $toDamager = $damager->getPosition()->subtractVector($entity->getPosition())->normalize();

            $dot = $direction->dot($toDamager);

            if ($dot > 0.5) {
                $event->setBaseDamage($event->getBaseDamage() * 2.5);
                $blindness = new \pocketmine\entity\effect\EffectInstance(\pocketmine\entity\effect\VanillaEffects::BLINDNESS(), 20 * 3, 0, false);
                $entity->getEffects()->add($blindness);
                $session->remove("rogue_active");
            }
        }

        if ($session->get("anti_trap_active")) {
            $session->addAntiTrapHit($entity);

            if ($session->getAntiTrapHits() >= 3) {
                $targetSession->set("trapped", true);
                $targetSession->set("trap_end", time() + 15);

                $entity->sendMessage("§cYou have been trapped! You cannot interact for 15 seconds!");
                $session->remove("anti_trap_active");
            }
        }
    }

    public function onInteract(PlayerInteractEvent $event): void {
        $player = $event->getPlayer();
        $session = $this->plugin->getSessionManager()->getSession($player);

        if ($session->get("trapped", false)) {
            $end = $session->get("trap_end", 0);
            if (time() < $end) {
                $block = $event->getBlock();
                $name = $block->getName();

                if (str_contains(strtolower($name), "chest") || str_contains(strtolower($name), "fence") || str_contains(strtolower($name), "gate")) {
                    $event->cancel();
                    $player->sendMessage("§cYou are trapped and cannot interact!");
                }
            } else {
                $session->remove("trapped");
                $session->remove("trap_end");
            }
        }
    }

    public function onPlace(BlockPlaceEvent $event): void {
        $player = $event->getPlayer();
        $session = $this->plugin->getSessionManager()->getSession($player);

        if ($session->get("trapped", false)) {
            $end = $session->get("trap_end", 0);
            if (time() < $end) {
                $event->cancel();
                $player->sendMessage("§cYou are trapped and cannot place blocks!");
            }
        }
    }
}
