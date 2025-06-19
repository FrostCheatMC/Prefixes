<?php

namespace frostcheat\prefixes\command;

use CortexPE\Commando\BaseCommand;
use CortexPE\Commando\constraint\InGameRequiredConstraint;

use muqsit\invmenu\InvMenu;
use muqsit\invmenu\transaction\InvMenuTransaction;
use muqsit\invmenu\transaction\InvMenuTransactionResult;
use muqsit\invmenu\type\InvMenuTypeIds;

use frostcheat\prefixes\Prefixes;

use pocketmine\block\VanillaBlocks;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use pocketmine\plugin\Plugin;
use pocketmine\utils\TextFormat;

class PrefixesCommand extends BaseCommand
{
    public function __construct(protected Plugin $plugin)
    {
        parent::__construct($this->plugin, "prefixes", "Prefixes list");
        $this->setPermission("prefixes.command");
        $this->setPermissionMessage(TextFormat::colorize(str_replace("%plugin-prefix%", Prefixes::getInstance()->getProvider()->getMessages()->get("plugin-prefix"), Prefixes::getInstance()->getProvider()->getMessages()->get("no-permission-command-message"))));
    }

    protected function prepare(): void
    {
        $this->addConstraint(new InGameRequiredConstraint($this));
    }

    public function onRun(CommandSender $sender, string $aliasUsed, array $args): void
    {
        if (!$sender instanceof Player) return;
        $this->sendPrefixesMenu($sender, 1);
    }

    private function sendPrefixesMenu(Player $player, int $page): void
    {
        $prefixes = array_values(Prefixes::getInstance()->getPrefixManager()->getPrefixes());
        $perPage = 44;
        $totalPages = max(1, (int)ceil(count($prefixes) / $perPage));
        $page = max(1, min($page, $totalPages));

        $menu = InvMenu::create(InvMenuTypeIds::TYPE_DOUBLE_CHEST);
        $inventory = $menu->getInventory();

        $start = ($page - 1) * $perPage;
        $items = array_slice($prefixes, $start, $perPage);
        $slot = 0;

        foreach ($items as $prefix) {
            $inventory->setItem($slot++, VanillaBlocks::BEACON()->asItem()
                ->setCustomName(TextFormat::colorize($prefix->getName()))
                ->setLore([
                    TextFormat::colorize("&8Prefix"),
                    " ",
                    TextFormat::colorize("&7Format: " . $prefix->getFormat()),
                ]));
        }

        $prev = VanillaBlocks::REDSTONE_TORCH()->asItem()->setCustomName("§cPrevious Page")->setLore(["§7Click to go back"]);
        $next = VanillaBlocks::REDSTONE_TORCH()->asItem()->setCustomName("§aNext Page")->setLore(["§7Click to continue"]);
        $inventory->setItem(45, $prev);
        $inventory->setItem(53, $next);

        $menu->setListener(function (InvMenuTransaction $transaction) use ($page, $player, $totalPages): InvMenuTransactionResult {
            $item = $transaction->getItemClicked();
            $prefixManager = Prefixes::getInstance()->getPrefixManager();
            $session = Prefixes::getInstance()->getSessionManager()->getSession($player->getName());
            $name = $item->getCustomName();

            if ($name === "§aNext Page" && $page < $totalPages) {
                $this->sendPrefixesMenu($player, $page + 1);
                return $transaction->discard();
            }
            if ($name === "§cPrevious Page" && $page > 1) {
                $this->sendPrefixesMenu($player, $page - 1);
                return $transaction->discard();
            }

            $prefix = $prefixManager->getPrefix($name);
            if ($prefix !== null && $session !== null) {
                if ($player->hasPermission($prefix->getPermission())) {
                    $session->setPrefix($prefix->getName());
                    $player->sendMessage(TextFormat::colorize(str_replace(["%plugin-prefix%", "%prefix%"], [
                        Prefixes::getInstance()->getProvider()->getMessages()->get("plugin-prefix"),
                        $prefix->getFormat()
                    ], Prefixes::getInstance()->getProvider()->getMessages()->get("player-sets-prefix-succesfuly"))));
                } else {
                    $player->sendMessage(TextFormat::colorize(str_replace("%plugin-prefix%", Prefixes::getInstance()->getProvider()->getMessages()->get("plugin-prefix"), Prefixes::getInstance()->getProvider()->getMessages()->get("player-sets-prefix-no-permission"))));
                }
            }

            return $transaction->discard();
        });

        $menu->send($player, TextFormat::colorize("&aPrefixes &7- Page $page"));
    }
}