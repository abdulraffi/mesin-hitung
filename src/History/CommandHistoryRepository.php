<?php


namespace Jakmall\Recruitment\Calculator\History;


class CommandHistoryRepository implements Infrastructure\CommandHistoryManagerInterface
{

    /**
     * @inheritDoc
     */
    public function findAll($driver, $command): array
    {
        return [];
    }

    /**
     * @inheritDoc
     */
    public function log($command): bool
    {
        // TODO: Implement log() method.
    }

    /**
     * @inheritDoc
     */
    public function clearAll(): bool
    {
        // TODO: Implement clearAll() method.
    }
}
