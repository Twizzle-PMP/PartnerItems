<?php

namespace Twizzle\PartnerItems;

use muqsit\invmenu\InvMenuHandler;
use pocketmine\plugin\PluginBase;
use Twizzle\PartnerItems\ability\AbilityManager;
use Twizzle\PartnerItems\ability\abilities\AntiTrap;
use Twizzle\PartnerItems\ability\abilities\Cactus;
use Twizzle\PartnerItems\ability\abilities\FlowerTank;
use Twizzle\PartnerItems\ability\abilities\GuardianAngel;
use Twizzle\PartnerItems\ability\abilities\Medkit;
use Twizzle\PartnerItems\ability\abilities\Ninja;
use Twizzle\PartnerItems\ability\abilities\Resistance;
use Twizzle\PartnerItems\ability\abilities\RiskyMode;
use Twizzle\PartnerItems\ability\abilities\Rogue;
use Twizzle\PartnerItems\ability\abilities\Stormbreaker;
use Twizzle\PartnerItems\ability\abilities\Strength;
use Twizzle\PartnerItems\ability\abilities\Switcher;
use Twizzle\PartnerItems\ability\abilities\Tank;
use Twizzle\PartnerItems\ability\abilities\TimeStone;
use Twizzle\PartnerItems\ability\abilities\TimeWarp;
use Twizzle\PartnerItems\ability\abilities\WebShooter;
use Twizzle\PartnerItems\command\AbilitiesCommand;
use Twizzle\PartnerItems\listener\CombatListener;
use Twizzle\PartnerItems\listener\ItemListener;
use Twizzle\PartnerItems\listener\PearlListener;
use Twizzle\PartnerItems\manager\ConfigManager;
use Twizzle\PartnerItems\manager\CooldownManager;
use Twizzle\PartnerItems\manager\SessionManager;

class Loader extends PluginBase {

    private static Loader $instance;
    private ConfigManager $configManager;
    private CooldownManager $cooldownManager;
    private SessionManager $sessionManager;
    private AbilityManager $abilityManager;

    protected function onLoad(): void {
        self::$instance = $this;
    }

    protected function onEnable(): void {
        $this->saveResource("config.yml");

        if (!InvMenuHandler::isRegistered()) {
            InvMenuHandler::register($this);
        }

        $this->configManager = new ConfigManager($this);
        $this->cooldownManager = new CooldownManager($this);
        $this->sessionManager = new SessionManager($this);
        $this->abilityManager = new AbilityManager($this);

        $this->registerAbilities();

        $this->getServer()->getPluginManager()->registerEvents(new ItemListener($this), $this);
        $this->getServer()->getPluginManager()->registerEvents(new CombatListener($this), $this);
        $this->getServer()->getPluginManager()->registerEvents(new PearlListener($this), $this);

        $this->getServer()->getCommandMap()->register("partneritems", new AbilitiesCommand($this));
    }

    private function registerAbilities(): void {
        $am = $this->abilityManager;

        $am->register(new Strength($this));
        $am->register(new Resistance($this));
        $am->register(new Ninja($this));
        $am->register(new Tank($this));
        $am->register(new RiskyMode($this));
        $am->register(new Cactus($this));
        $am->register(new Rogue($this));
        $am->register(new TimeWarp($this));
        $am->register(new AntiTrap($this));
        $am->register(new Switcher($this));
        $am->register(new FlowerTank($this));
        $am->register(new Medkit($this));
        $am->register(new TimeStone($this));
        $am->register(new GuardianAngel($this));
        $am->register(new WebShooter($this));
        $am->register(new Stormbreaker($this));
    }

    public static function getInstance(): Loader {
        return self::$instance;
    }

    public function getConfigManager(): ConfigManager {
        return $this->configManager;
    }

    public function getCooldownManager(): CooldownManager {
        return $this->cooldownManager;
    }

    public function getSessionManager(): SessionManager {
        return $this->sessionManager;
    }

    public function getAbilityManager(): AbilityManager {
        return $this->abilityManager;
    }
}