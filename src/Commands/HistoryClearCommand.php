<?php


namespace Jakmall\Recruitment\Calculator\Commands;


use Illuminate\Console\Command;
use Jakmall\Recruitment\Calculator\History\Infrastructure\CommandHistoryManagerInterface;

class HistoryClearCommand extends Command
{
    protected $signature = 'history:clear';

    protected $description = "Clear saved history";

    public function handle(CommandHistoryManagerInterface $historyManager): void
    {
        $historyManager->clearAll();
        $this->comment("History cleared!");
    }

}
