<?php

namespace Twizzle\PartnerItems\command;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\item\enchantment\EnchantmentInstance;
use pocketmine\item\enchantment\VanillaEnchantments;
use pocketmine\item\StringToItemParser;
use pocketmine\player\Player;
use Twizzle\PartnerItems\Loader;
use Twizzle\PartnerItems\menu\AbilitiesMenu;

class AbilitiesCommand extends Command {

    private Loader $plugin;

    public function __construct(Loader $plugin) {
        $this->plugin = $plugin;
        parent::__construct("abilities", "Partner items command", "/abilities <all|view>", ["ability", "partner"]);
        $this->setPermission("partneritems.command.abilities");
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args): bool {
        if (!$sender instanceof Player) {
            $sender->sendMessage("§cUse this command in-game!");
            return false;
        }

        if (!$this->testPermission($sender)) {
            $sender->sendMessage("§cYou don't have permission!");
            return false;
        }

        if (empty($args)) {
            $sender->sendMessage("§cUsage: /abilities <all|view>");
            return false;
        }

        $sub = strtolower($args[0]);

        if ($sub === "view") {
            $menu = new AbilitiesMenu($this->plugin);
            $menu->send($sender);
            return true;
        }

        if ($sub === "all") {
            $items = [
                ["blaze_powder", "Strength", "Grants Strength II for 8 seconds", "strength", "§c"],
                ["iron_ingot", "Resistance", "Grants Resistance III for 6 seconds", "resistance", "§7"],
                ["shears", "Ninja", "Teleports behind last player who hit you", "ninja", "§b"],
                ["golden_apple", "Tank", "Grants Absorption IV for 30 seconds", "tank", "§6"],
                ["gold_nugget", "Risky Mode", "Strength III then Weakness I", "risky_mode", "§4"],
                ["cactus", "Cactus", "Strength II, Regen III, Speed III", "cactus", "§a"],
                ["golden_sword", "Rogue", "Backstab deals 2.5x damage", "rogue", "§e"],
                ["feather", "Time Warp", "Teleport to last thrown pearl", "time_warp", "§d"],
                ["bed", "Anti-Trap", "Trap enemies after 3 hits", "anti_trap", "§5"],
                ["snowball", "Switcher", "Swap positions with enemy", "switcher", "§f"],
                ["poppy", "Flower Tank", "Regeneration IV and Resistance III", "flower_tank", "§2"],
                ["paper", "Medkit", "Regen IV, Absorption VI, Resistance III", "medkit", "§9"],
                ["green_dye", "Time Stone", "Clear all cooldowns", "time_stone", "§3"],
                ["clock", "Guardian Angel", "Passive heal when low health", "guardian_angel", "§1"],
                ["string", "Web Shooter", "Trap enemies in web", "web_shooter", "§8"],
                ["golden_axe", "Stormbreaker", "Remove enemy helmet after 3 hits", "stormbreaker", "§4"]
            ];

            $given = 0;
            foreach ($items as $data) {
                $parsed = StringToItemParser::getInstance()->parse($data[0]);
                $item = $parsed ?? \pocketmine\item\VanillaItems::DIAMOND_SWORD();

                $item->setCustomName("§r§l" . $data[4] . $data[1]);
                $lore = ["§r§7" . $data[2], "", "§r§cPartner Item"];
                $item->setLore($lore);

                $unbreaking = VanillaEnchantments::UNBREAKING();
                $item->addEnchantment(new EnchantmentInstance($unbreaking, 1));

                $nbt = $item->getNamedTag();
                $nbt->setString("partner_id", $data[3]);

                if (!$sender->getInventory()->canAddItem($item)) {
                    $sender->sendMessage("§cYour inventory is full! Could not give all items.");
                    return false;
                }

                $sender->getInventory()->addItem($item);
                $given++;
            }

            $sender->sendMessage("§aYou received all §c" . $given . " §apartner abilities!");
            return true;
        }

        $sender->sendMessage("§cUnknown subcommand! Use: /abilities <all|view>");
        return false;
    }
}