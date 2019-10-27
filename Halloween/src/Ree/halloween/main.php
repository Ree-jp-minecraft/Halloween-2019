<?php

namespace Ree\halloween;

use pocketmine\event\inventory\InventoryTransactionEvent;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\player\PlayerQuitEvent;
use pocketmine\inventory\ArmorInventory;
use pocketmine\inventory\transaction\action\SlotChangeAction;
use pocketmine\item\Item;
use pocketmine\plugin\PluginBase;
use pocketmine\event\Listener;

class main extends PluginBase implements Listener
{
    /**
     * @var Item[]
     */
    private $item;

    public function onEnable()
    {
        $this->getServer()->getPluginManager()->registerEvents($this, $this);
        $this->getServer()->getLogger()->info("読み込みました");
    }

    public function onJoin(PlayerJoinEvent $ev)
    {
        $p = $ev->getPlayer();
        $n = $p->getName();

        $this->item[$n] = $p->getArmorInventory()->getItem(0);
        $p->getArmorInventory()->setItem(0, Item::get(Item::PUMPKIN));
    }

    public function onQuit(PlayerQuitEvent $ev)
    {
        $p = $ev->getPlayer();
        $n = $p->getName();

        $p->getArmorInventory()->setItem(0, $this->item[$n]);
    }

    public function onTrance(InventoryTransactionEvent $ev)
    {
        $tr = $ev->getTransaction();
        $inve = $tr->getInventories();
        $cansel = false;

        foreach ($inve as $inv) {
            foreach ($tr->getActions() as $action) {
                if ($action instanceof SlotChangeAction) {
                    if ($action->getInventory() instanceof ArmorInventory) {
                        if ($action->getSlot() === 0) {
                            $ev->setCancelled();
                        }
                    }
                }
            }
        }
    }
}