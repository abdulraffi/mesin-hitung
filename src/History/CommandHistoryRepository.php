<?php


namespace Jakmall\Recruitment\Calculator\History;


use http\Exception\InvalidArgumentException;
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
            return $this->driverDatabase($command, $columns, false);
        } else {
            return $this->driverFile($command, $columns, false);
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

    /**
     * @inheritDoc
     */
    public function getHistoryById($id, $driver)
    {
        if($driver == 'database') {
            return History::find($id);
        } else {
            return $this->findInFile($id);
        }
    }

    /**
     * @inheritDoc
     */
    public function findAllApi($driver)
    {
        $columns = ['command', 'description', 'result', 'output', 'created_at', 'id'];
        if($driver == "database") {
            return $this->driverDatabase(null, $columns, true);
        } else {
            return $this->driverFile(null, $columns, true);
        }
    }

    protected function driverDatabase($command, $columns, $is_api)
    {
        $histories = History::query();
        $histories->select($columns);
        if($command) {
            $histories->whereIn('command', array_map('ucfirst', $command));
        }
        $result = $histories->get();

        if ($is_api)
            return $result;

        return collect($result->toArray())->map(function ($item, $key) {
            return array_merge(["no" => ($key + 1)], $item);
        })->all();
    }

    protected function driverFile($commands, $columns, $is_api)
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

            if($is_api)
                return $histories;

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

    protected function findInFile($id)
    {
        try {
            $historiesFile = file_get_contents($this->file);
            $histories = collect(json_decode($historiesFile, true))->values();

            $histories = $histories->whereIn(
                'id', $id)->values();

            if ($histories->count() == 0)
                return false;

            return $histories;
        } catch (\Exception $e) {
            return [];
        }
    }
}
