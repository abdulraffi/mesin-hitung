<?php


namespace Jakmall\Recruitment\Calculator\Commands;

use Illuminate\Console\Command;
use Jakmall\Recruitment\Calculator\History\Infrastructure\CommandHistoryManagerInterface;

class HistoryListCommand extends Command
{

    protected $signature;

    protected $description = 'Show calculator history';

    public function __construct()
    {
        $commandVerb = 'history:list';
        $commandDescription = 'Filter the history by commands';
        $optionDescription = 'Driver for storage connection';
        $this->signature = sprintf(
            '%s {commands?* : %s} {--D|driver=database : %s}',
            $commandVerb, $commandDescription, $optionDescription
        );

        parent::__construct();
    }

    public function handle(CommandHistoryManagerInterface $historyManager): void
    {
        $commands = $this->argument('commands');
        $driver = $this->option('driver');

        $history = $historyManager->findAll($commands, $driver);
        if(!empty($history)) {
            $headers = ['No', 'Command', 'Description', 'Result', 'Output', 'Time'];
            $this->table($headers, $history);
        } else {
            $this->comment("History is empty");
        }

    }

}
