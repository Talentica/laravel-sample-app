<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/

Route::group(array('prefix' => 'v1', 'before' => 'api.auth|api.limit'), function()
{


    Route::resource('lists', 'ListController');
	// Get tasks for list
	Route::get('lists/{id}/tasks', function($id)
	{
		$list = TaskList::findByOwnerAndId(Auth::user(), $id);

		return Response::json($list->tasks->toArray());
	})->where('id', '\d+');

	// Create task
	Route::post('lists/{id}/tasks', function($id)
	{
		$list = TaskList::findByOwnerAndId(Auth::user(), $id);

		$task = new Task(Input::get());
		$task->validate();
		$task->list_id = $id;

		if (!$task->save())
		{
			App::abort(500, 'Task was not saved');
		}

		return Response::json($task->toArray(), 201);
	})->where('id', '\d+');

	// Get task by ID
	Route::get('lists/{list_id}/tasks/{id}', function($list_id, $id)
	{
		$list = TaskList::findByOwnerAndId(Auth::user(), $list_id);

		$task = $list->tasks()->find($id);

		if (!$task)
		{
			App::abort(404);
		}

		return Response::json($task->toArray());
	})->where('list_id', '\d+')->where('id', '\d+');

	// Update task
	Route::put('lists/{list_id}/tasks/{id}', function($list_id, $id)
	{
		$list = TaskList::findByOwnerAndId(Auth::user(), $list_id);

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

		return Response::json($task->toArray());
	})->where('list_id', '\d+')->where('id', '\d+');

	// Delete task
	Route::delete('lists/{list_id}/tasks/{id}', function($list_id, $id)
	{
		$list = TaskList::findByOwnerAndId(Auth::user(), $list_id);

		$task = $list->tasks()->find($id);

		if (!$task)
		{
			App::abort(404);
		}

		$task->delete();

		return Response::make(null, 204);
	})->where('list_id', '\d+')->where('id', '\d+');
});