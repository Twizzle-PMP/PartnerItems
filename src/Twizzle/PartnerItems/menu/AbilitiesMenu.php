<?php

namespace Twizzle\PartnerItems\menu;

use muqsit\invmenu\InvMenu;
use muqsit\invmenu\transaction\InvMenuTransaction;
use muqsit\invmenu\type\InvMenuTypeIds;
use pocketmine\block\VanillaBlocks;
use pocketmine\item\enchantment\EnchantmentInstance;
use pocketmine\item\enchantment\VanillaEnchantments;
use pocketmine\item\Item;
use pocketmine\item\StringToItemParser;
use pocketmine\player\Player;
use Twizzle\PartnerItems\Loader;

class AbilitiesMenu {

    private Loader $plugin;

    public function __construct(Loader $plugin) {
        $this->plugin = $plugin;
    }

    public function send(Player $player): void {
        $menu = InvMenu::create(InvMenuTypeIds::TYPE_DOUBLE_CHEST);
        $menu->setName("§l§cPartner Abilities");

        $abilities = [
            ["blaze_powder", "Strength", "Grants Strength II for 8 seconds", "§c"],
            ["iron_ingot", "Resistance", "Grants Resistance III for 6 seconds", "§7"],
            ["shears", "Ninja", "Teleports behind last player who hit you", "§b"],
            ["golden_apple", "Tank", "Grants Absorption IV for 30 seconds", "§6"],
            ["gold_nugget", "Risky Mode", "Strength III then Weakness I", "§4"],
            ["cactus", "Cactus", "Strength II, Regen III, Speed III", "§a"],
            ["golden_sword", "Rogue", "Backstab deals 2.5x damage", "§e"],
            ["feather", "Time Warp", "Teleport to last thrown pearl", "§d"],
            ["bed", "Anti-Trap", "Trap enemies after 3 hits", "§5"],
            ["snowball", "Switcher", "Swap positions with enemy", "§f"],
            ["poppy", "Flower Tank", "Regeneration IV and Resistance III", "§2"],
            ["paper", "Medkit", "Regen IV, Absorption VI, Resistance III", "§9"],
            ["green_dye", "Time Stone", "Clear all cooldowns", "§3"],
            ["clock", "Guardian Angel", "Passive heal when low health", "§1"],
            ["string", "Web Shooter", "Trap enemies in web", "§8"],
            ["golden_axe", "Stormbreaker", "Remove enemy helmet after 3 hits", "§4"]
        ];

        $inventory = $menu->getInventory();

        $redGlass = VanillaBlocks::STAINED_GLASS()->setColor(\pocketmine\block\utils\DyeColor::RED())->asItem();
        $redGlass->setCustomName("§r§c");

        $borderSlots = [
            0, 1, 2, 3, 4, 5, 6, 7, 8,
            9, 17,
            18, 26,
            27, 35,
            36, 37, 38, 39, 40, 41, 42, 43, 44,
            45, 46, 47, 48, 49, 50, 51, 52, 53
        ];

        foreach ($borderSlots as $slot) {
            $inventory->setItem($slot, $redGlass);
        }

        $abilitySlots = [
            10, 11, 12, 13, 14, 15, 16,
            19, 20, 21, 22, 23, 24, 25,
            28, 29, 30, 31, 32, 33, 34
        ];

        foreach ($abilities as $i => $data) {
            if (!isset($abilitySlots[$i])) {
                break;
            }

            $slot = $abilitySlots[$i];
            $parsed = StringToItemParser::getInstance()->parse($data[0]);
            $item = $parsed ?? \pocketmine\item\VanillaItems::DIAMOND_SWORD();

            $item->setCustomName("§r§l" . $data[3] . $data[1]);
            $item->setLore(["§r§7" . $data[2], "", "§r§cInformation Only"]);

            $unbreaking = VanillaEnchantments::UNBREAKING();
            $item->addEnchantment(new EnchantmentInstance($unbreaking, 1));

            $inventory->setItem($slot, $item);
        }

        $menu->setListener(function(InvMenuTransaction $transaction): \muqsit\invmenu\transaction\InvMenuTransactionResult {
            $player = $transaction->getPlayer();
            $item = $transaction->getItemClicked();
            $name = $item->getCustomName();

            if ($name === "§r§c") {
                return $transaction->discard();
            }

            $player->sendMessage("§c" . $name . " §r§7- This is just a preview!");
            return $transaction->discard();
        });

        $menu->send($player);
    }
}