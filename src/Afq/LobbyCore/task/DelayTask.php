<?php

namespace Afq\LobbyCore\task;

use pocketmine\player\Player;
use pocketmine\scheduler\Task;

use Afq\LobbyCore\Main;

class DelayTask extends Task {

    public $main;
    public $playerName;

    public function __construct(Main $main, String $playerName) {
        $this->main = $main;
        $this->playerName = $playerName;
    }

    public function onRun() : void {
        $player = $this->main->getServer()->getPlayerExact($this->playerName);
        if ($player instanceof Player) {
        }
    }

}
