<?php

namespace Twizzle\PartnerItems\manager;

use pocketmine\utils\Config;
use Twizzle\PartnerItems\Loader;

class ConfigManager {

    private Loader $plugin;
    private Config $config;

    public function __construct(Loader $plugin) {
        $this->plugin = $plugin;
        $this->config = $plugin->getConfig();
    }

    public function getPrefix(): string {
        return $this->config->get("prefix", "§8[§cPartnerItems§8]§r ");
    }

    public function getCooldown(string $ability): int {
        $list = $this->config->get("cooldowns", []);
        return $list[$ability] ?? $list["global"] ?? 10;
    }

    public function getMessage(string $key, array $tags = []): string {
        $messages = $this->config->get("messages", []);
        $text = $messages[$key] ?? $key;

        foreach ($tags as $find => $replace) {
            $text = str_replace("{" . $find . "}", (string)$replace, $text);
        }

        return $text;
    }
}
