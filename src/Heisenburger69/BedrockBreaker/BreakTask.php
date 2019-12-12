<?php

namespace Heisenburger69\BedrockBreaker;

use pocketmine\block\Block;
use pocketmine\item\Item;
use pocketmine\level\particle\ItemBreakParticle;
use pocketmine\math\Vector3;
use pocketmine\scheduler\Task;

class BreakTask extends Task
{
    private $block;
    /**
     * @var Main
     */
    private $plugin;

    /**
     * BreakTask constructor.
     * @param Main $plugin
     * @param $block
     */
    public function __construct(\Heisenburger69\BedrockBreaker\Main $plugin, $block)
    {
        $this->plugin = $plugin;
        $this->block = $block;
    }

    /**
     * Actions to execute when run
     *
     * @param int $currentTick
     *
     * @return void
     */
    public function onRun(int $currentTick)
    {
        $x = $this->block->getX();
        $y = $this->block->getY();
        $z = $this->block->getZ();
        $level = $this->block->getLevel()->getName();
        $loc = new Vector3($x, $y, $z);
        $blevel = $this->plugin->getServer()->getLevelByName($level);
        $blevel->setBlock($loc, Block::get(Block::AIR));
        $blevel->addParticle(new ItemBreakParticle($loc, Item::get(Item::BEDROCK)));
        $blevel->dropItem($loc, Item::get(Item::BEDROCK));
    }
}