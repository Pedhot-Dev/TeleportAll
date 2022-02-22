<?php

namespace pedhot\teleportall;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use pocketmine\Server;
use pocketmine\utils\TextFormat;

class TpAllCommand extends Command
{

    public function __construct(string $name)
    {
        parent::__construct($name, "Teleport all players to you", null, ["tpall"]);
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args)
    {
        if ($this->testPermission($sender)) return;
        if (!$sender instanceof Player) {
            $sender->sendMessage(TextFormat::RED . "Execute this command in game!");
            return;
        }
        if (count($args) >= 1) {
            if (($selectedPlayer = Server::getInstance()->getPlayerExact($args[0])) == null) {
                $sender->sendMessage(TextFormat::RED . "Player with name " . $args[0] . " currently offline!");
                return;
            }
            $players = [];
            foreach (Server::getInstance()->getOnlinePlayers() as $onlinePlayer) {
                if (!$onlinePlayer->isOnline() && $onlinePlayer->getId() === $sender->getId()) return;
                $players[] = $onlinePlayer;
            }
            APTeleportAll::teleportTo($sender, $players, $selectedPlayer);
            return;
        }
        $players = [];
        foreach (Server::getInstance()->getOnlinePlayers() as $onlinePlayer) {
            if (!$onlinePlayer->isOnline() && $onlinePlayer->getId() === $sender->getId()) return;
            $players[] = $onlinePlayer;
        }
        APTeleportAll::teleportTo($sender, $players);
    }

}