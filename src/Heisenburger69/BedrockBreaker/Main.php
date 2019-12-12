<?php

declare(strict_types=1);

namespace Heisenburger69\BedrockBreaker;

use pocketmine\block\Block;
use pocketmine\block\BlockFactory;
use pocketmine\event\block\BlockBreakEvent;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\item\Item;
use pocketmine\nbt\tag\StringTag;
use pocketmine\Player;
use pocketmine\plugin\PluginBase;
use pocketmine\command\CommandSender;
use pocketmine\command\Command;
use pocketmine\utils\Config;
use pocketmine\utils\TextFormat as C;

class Main extends PluginBase implements Listener {

    /* @var Config*/
    public $config;

	public function onEnable() : void{
        BlockFactory::registerBlock(new BurgerBedrock($this), true);
        $this->getServer()->getPluginManager()->registerEvents($this, $this);
        $this->config = new Config($this->getDataFolder()."config.yml", Config::YAML, ["Break Time" => 3, "BedrockPick Lore" => "This Pickaxe can break Bedrock", "BedrockPick Name" => "Bedrock Breaker"]);
    }

	public function onCommand(CommandSender $sender, Command $command, string $label, array $args) : bool{
		switch($command->getName()){
			case "bedrockpick":
			    if($sender->hasPermission("bedrock.givepick")) {
                    if (isset($args[0])) {
                        $player = $this->getServer()->getPlayer($args[0]);
                        if ($player === null) {
                            $sender->sendMessage(C::RED . "Player not online!");
                            return false;
                        }
                        $this->giveBedrockPick($player);
                        return true;
                    }
                }
                    return true;
			default:
				return false;
		}
	}

    public function giveBedrockPick(Player $player)
    {
        $pick = Item::get(Item::DIAMOND_PICKAXE, 0, 1);
        $name = (string)$this->config->get("BedrockPick Name");
        $pick->setCustomName(C::RESET.$name);
        $rawlore = (string)$this->config->get("BedrockPick Lore");
        $lore = str_split($rawlore, 25);
        $pick->setLore($lore);
        $pick->setNamedTagEntry(new StringTag("bedrockpick", "reeee"));
        $player->getInventory()->addItem($pick);
    }

    public function onBreak(PlayerInteractEvent $event) {
	    if($event->getAction() === PlayerInteractEvent::RIGHT_CLICK_BLOCK) {
            $player = $event->getPlayer();
            $item = $event->getItem();
            $block = $event->getBlock();
            if ($block->getId() === Block::BEDROCK) {
                if ($player->hasPermission("bedrock.break")) {
                    $time = (int)$this->config->get("Break Time") * 20;
                    $this->getScheduler()->scheduleDelayedTask(new BreakTask($this, $block), $time);
                    return;
                }
                $nbt = $item->getNamedTagEntry("bedrockpick");
                if ($nbt !== null && $player->hasPermission("bedrock.usepick")) {
                    $time = (int)$this->config->get("Break Time") * 20;
                    $this->getScheduler()->scheduleDelayedTask(new BreakTask($this, $block), $time);
                    return;
                }
            }
        }
    }
}
