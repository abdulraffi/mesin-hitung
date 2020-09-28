<?php

namespace Jakmall\Recruitment\Calculator\Http\Controller;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Jakmall\Recruitment\Calculator\Connection;
use Jakmall\Recruitment\Calculator\History\Infrastructure\CommandHistoryManagerInterface;
use Jakmall\Recruitment\Calculator\Models\History;

class HistoryController
{

    private $historyManager;
    /**
     * @var string[]
     */
    private $delimiter;

    public function __construct(CommandHistoryManagerInterface $historyManager)
    {
        $this->delimiter = [
            'add' => ' + ',
            'subtract' => ' - ',
            'multiply' => ' * ',
            'divide' => ' / ',
            'pow' => ' ^ '
        ];
        $this->historyManager = $historyManager;
        new Connection();
    }

    public function index(Request $request)
    {
        $driver = $request->input('driver');
        if($driver)
            $history = $this->historyManager->findAllApi($request->input('driver'));
        else
            $history = $this->historyManager->findAllApi('database');


        $data = [];
        foreach ($history as $item) {
            $data[] = $this->responseStructure($item);
        }

        return Response::create($data,200);
    }

    public function show(Request $request, $id)
    {
        $driver = $request->input('driver');
        if($driver)
            $history = $this->historyManager->getHistoryById($id,$request->input('driver'));
        else
            $history = $this->historyManager->getHistoryById($id,'database');

        if(!$history)
            return Response::create('',404);

        $data = $this->responseStructure($history);

        return Response::create($data,200);
    }

    public function remove($id)
    {
        $file = __DIR__."/../../../storage/history.json";
        try {
            $history = History::findOrFail($id);
            $history->delete();

            $historiesFile = file_get_contents($file);
            echo ($historiesFile);
            $histories = collect(json_decode($historiesFile, true))
                ->filter(function ($item) use ($id) {
                    return $item['id'] <> $id;
                })->values()->toJson();
            file_put_contents($file, $histories);

            return Response::create('', 204);
        } catch (ModelNotFoundException $e) {
            return Response::create('', 404);
        }

    }

    private function responseStructure($history){
        $command = strtolower($history['command']);
        $input = explode($this->delimiter[$command], $history['description']);
        return [
            'id' => 'id'.$history['id'],
            'command' => $command,
            'operation' => $history['description'],
            'input' => array_map('floatval', $input),
            'result' => (float)$history['result'],
            'time' => $history['created_at']
        ];
    }
}
