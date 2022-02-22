<?php

namespace pedhot\teleportall;

use JackMD\ConfigUpdater\ConfigUpdater;
use pocketmine\player\Player;
use pocketmine\plugin\PluginBase;
use pocketmine\Server;

class APTeleportAll extends PluginBase
{

    private const LATEST_VERSION = 1.0;

    private static self $instance;

    protected function onLoad(): void
    {
        self::$instance = $this;
    }

    protected function onEnable(): void
    {
        $this->saveDefaultConfig();
        ConfigUpdater::checkUpdate($this, $this->getConfig(), "config-version", self::LATEST_VERSION);
        Server::getInstance()->getCommandMap()->register($this->getName(), new TpAllCommand("teleportall"));
    }

    /**
     * @param Player $player
     * @param Player[] $players
     * @param Player|null $selectedPlayer
     * @return void
     */
    public static function teleportTo(Player $player, array $players, Player $selectedPlayer = null)
    {
        $config = self::$instance->getConfig();
        foreach ($players as $playerA) {
            if ($selectedPlayer == null) {
                $playerA->teleport($player->getPosition());
                $player->sendMessage(str_replace("{PLAYER}", "you", $config->getNested("message.success-teleport", "Teleported all player to {PLAYER} success")));
            }else {
                $playerA->teleport($selectedPlayer->getPosition());
                $player->sendMessage(str_replace("{PLAYER}", $selectedPlayer->getName(), $config->getNested("message.success-teleport", "Teleported all player to {PLAYER} success")));
            }
        }
    }

}