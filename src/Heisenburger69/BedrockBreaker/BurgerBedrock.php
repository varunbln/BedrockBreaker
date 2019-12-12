<?php

namespace Heisenburger69\BedrockBreaker;

use pocketmine\block\Bedrock;
use pocketmine\item\Item;

class BurgerBedrock extends Bedrock
{
    /**
     * @var Main
     */
    private $plugin;

    /**
     * BurgerBedrock constructor.
     * @param Main $plugin
     * @param int $meta
     */
    public function __construct(Main $plugin, $meta = 0)
    {
        $this->plugin = $plugin;
        parent::__construct($meta);
    }

    public function getBreakTime(Item $item): float
    {
        $break = (int)$this->plugin->config->get("Break Time");
        return $break;
    }
}