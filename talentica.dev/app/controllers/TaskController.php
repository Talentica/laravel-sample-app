<?php


class TaskController extends BaseController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index($parent_id)
	{
        $list = TaskList::findByOwnerAndId(Auth::user(), $parent_id);

        return Response::json($list->tasks->toArray());
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create($parent_id)
	{
        $list = TaskList::findByOwnerAndId(Auth::user(), $parent_id);

        $task = new Task(Input::get());
        $task->validate();
        $task->list_id = $parent_id;

        if (!$task->save())
        {
            App::abort(500, 'Task was not saved');
        }

        return Response::json($task->toArray(), 201);
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		//
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($parent_id, $id)
	{
        $list = TaskList::findByOwnerAndId(Auth::user(), $parent_id);

        $task = $list->tasks()->find($id);

        if (!$task)
        {
            App::abort(404);
        }

        return Response::json($task->toArray());
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($parent_id, $id)
	{
        $list = TaskList::findByOwnerAndId(Auth::user(), $parent_id);

        $task = $list->tasks()->find($id);

        if (!$task)
        {
            App::abort(404);
        }

        $task->fill(Input::get());
        $task->validate();

        if (!$task->save())
        {
            App::abort(500, 'Task was not updated');
        }

    }

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($parent_id, $id)
	{
        $list = TaskList::findByOwnerAndId(Auth::user(), $parent_id);

        $task = $list->tasks()->find($id);

        if (!$task)
        {
            App::abort(404);
        }

        $task->fill(Input::get());
        $task->validate();

        if (!$task->save())
        {
            App::abort(500, 'Task was not updated');
        }
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($parent_id, $id)
	{
        $list = TaskList::findByOwnerAndId(Auth::user(), $parent_id);

        $task = $list->tasks()->find($id);

        if (!$task)
        {
            App::abort(404);
        }

        $task->delete();

        return Response::make(null, 204);
	}

}