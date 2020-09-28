<?php


namespace Jakmall\Recruitment\Calculator\History;


use Jakmall\Recruitment\Calculator\Models\History;
use Jakmall\Recruitment\Calculator\Connection;
use Jakmall\Recruitment\Calculator\History\Infrastructure\CommandHistoryManagerInterface;

class CommandHistoryRepository implements CommandHistoryManagerInterface
{

    private $file;

    public function __construct()
    {
        $this->file = __DIR__."/../../storage/history.json";
        new Connection();
    }

    /**
     * @inheritDoc
     */
    public function findAll($command, $driver): array
    {
        $columns = ['command', 'description', 'result', 'output', 'created_at'];
        if($driver == "database") {
            return $this->driverDatabase($command, $columns);
        } else {
            return $this->driverFile($command, $columns);
        }

    }

    /**
     * @inheritDoc
     */
    public function log($command): bool
    {
        $history = History::create($command);
        if($history) {
            if($this->saveToFile($history)) {
                return true;
            }
        }
        return false;
    }

    /**
     * @inheritDoc
     */
    public function clearAll(): bool
    {
        $truncate_db = History::query()->truncate();
        if($truncate_db) {
            try {
                $cleared = json_encode([]);
                if(file_put_contents($this->file, $cleared)) {
                    return true;
                }
            } catch (\Exception $e) {
                return false;
            }
        }
        return false;
    }

    protected function driverDatabase($command, $columns)
    {
        $histories = History::query();
        $histories->select($columns);
        if($command) {
            $histories->whereIn('command', array_map('ucfirst', $command));
        }
        $result = $histories->get();
        return collect($result->toArray())->map(function ($item, $key) {
            return array_merge(["no" => ($key + 1)], $item);
        })->all();
    }

    protected function driverFile($commands, $columns)
    {
        try {
            $historiesFile = file_get_contents($this->file);
            $histories = collect(json_decode($historiesFile, true))
                ->map(function ($item) use ($columns) {
                    return collect($item)
                        ->only($columns)
                        ->all();
                })->values();

            if($commands) {
                $histories = $histories->whereIn(
                    'command', array_map('ucfirst', $commands))->values();
            }

            return $histories->map(function ($item, $key) {
                return array_merge(["no" => ($key + 1)], $item);
            })->all();
        } catch (\Exception $e) {
            return [];
        }
    }

    protected function saveToFile($data)
    {
        try {
            $historiesFile = file_get_contents($this->file);
            $histories = json_decode($historiesFile, true);

            array_push($histories, $data);
            $newFile = json_encode($histories, JSON_PRETTY_PRINT);
            if(file_put_contents($this->file, $newFile)) {
                return true;
            }
            return false;
        } catch (\Exception $e) {
            return false;
        }
    }
}
