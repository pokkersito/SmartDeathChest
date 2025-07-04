<?php

namespace DeathChest;

use pocketmine\event\Listener;
use pocketmine\block\VanillaBlocks;
use pocketmine\event\player\PlayerDeathEvent;
use pocketmine\block\tile\Chest as TileChest;
use pocketmine\math\Facing;
use pocketmine\player\Player;
use pocketmine\world\sound\ExplodeSound;
use pocketmine\world\particle\HugeExplodeParticle;
use pocketmine\scheduler\ClosureTask;
use pocketmine\scheduler\CancelTaskException;
use pocketmine\math\Vector3;
use DeathChest\Main;
use pocketmine\world\particle\FloatingTextParticle;

class EventListener implements Listener {

    public function onDeath(PlayerDeathEvent $event): void {
        $player = $event->getPlayer();
        $world = $player->getWorld();

        $pos1 = $player->getPosition()->floor()->add(0, 0, 1);
        $pos2 = $player->getPosition()->floor()->add(1, 0, 1); 

        $chest1 = VanillaBlocks::CHEST()->setFacing(Facing::NORTH);
        $chest2 = VanillaBlocks::CHEST()->setFacing(Facing::NORTH);

        $world->setBlock($pos1, $chest1);
        $world->setBlock($pos2, $chest2);

        $event->setDrops([]);

        $tile1 = $world->getTile($pos1);
        $tile2 = $world->getTile($pos2);

        if ($tile1 instanceof TileChest && $tile2 instanceof TileChest) {
            $tile1->pairWith($tile2);

            $inventory = $tile1->getInventory();
            $maxSlots = $inventory->getSize();
            $reservedSlots = [47, 48, 50, 51];
            $slot = 0;

            foreach ($player->getInventory()->getContents() as $index => $item) {
                if (in_array($index, $reservedSlots)) $slot++;
                if ($slot >= $maxSlots) break;
                $inventory->setItem($slot++, $item);
            }

            $armorContents = $player->getArmorInventory()->getContents();
            foreach ($armorContents as $index => $armor) {
                if (isset($reservedSlots[$index])) {
                    $inventory->setItem($reservedSlots[$index], $armor);
                }
            }
            
            $textPosition = $pos1->add(1, 1, 1);
            $name = $player->getName();
            $config = Main::getInstance()->getPluginConfig();
            $timeLeft =  $config->get("time", 60);
            $floatingText = new FloatingTextParticle("Loot of §b{$name}\n§e00:{$timeLeft}");
            $world->addParticle($textPosition, $floatingText);

            Main::getInstance()->getScheduler()->scheduleRepeatingTask(new ClosureTask(function() use (&$timeLeft, $world, $textPosition, &$floatingText, $name) {
                $timeLeft--;
                if ($timeLeft <= 0) {
                    throw new CancelTaskException();
                }
                $seconds = str_pad((string)$timeLeft, 2, "0", STR_PAD_LEFT);
                $floatingText->setText("Loot of §b{$name}\n§e00:{$seconds}");
                $world->addParticle($textPosition, $floatingText);
            }), 20);

            Main::getInstance()->getScheduler()->scheduleDelayedTask(new ClosureTask(function() use ($world, $pos1, $pos2, $floatingText, $textPosition) {
                $tile = $world->getTile($pos1);
                if ($tile instanceof TileChest) {
                    foreach ($tile->getInventory()->getContents() as $item) {
                        $world->dropItem($pos1->add(0.5, 1, 0.5), $item);
                    }
                }
                $world->setBlock($pos1, VanillaBlocks::AIR());
                $world->setBlock($pos2, VanillaBlocks::AIR());
                $world->addSound($pos1->add(0.5, 1, 0.5), new ExplodeSound());
                $world->addParticle($pos1->add(0.5, 0.5, 0.5), new HugeExplodeParticle());
                $floatingText->setInvisible();
                $world->addParticle($textPosition, $floatingText);
                $config = Main::getInstance()->getPluginConfig();
                $timeLeft =  $config->get("time", 60);
            }), $timeLeft * 20);
        }
    }
}
