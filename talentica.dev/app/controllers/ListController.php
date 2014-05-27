<?php

namespace Talentica\Controller;

class ListController extends BaseController {

    protected $user; //Protected to allow overriding in tests

    public function __construct()
    {
            $this->user = \Auth::user();
    }

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
        $lists = $this->user->tasklists;

        return \Response::json($lists->toArray());
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		$list = new \TaskList(Input::get());
        $list->validate();
        $list->user_id = $this->user->id;

        if (!$list->save())
        {
            \App::abort(500, 'List was not saved');
        }

        return \Response::json($list->toArray(), 201);
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
	public function show($id)
	{
        $list = \TaskList::findByOwnerAndId($this->user, $id);

        return \Response::json($list->toArray());
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{

	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */

	public function update($id)
	{
        $list = \TaskList::findByOwnerAndId($this->user(), $id);
        $list->fill(Input::get());
        $list->validate();

        if (!$list->save())
        {
            \App::abort(500, 'List was not updated');
        }

        return \Response::json($list->toArray());//
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */

	public function destroy($id)
	{
        $list = \TaskList::findByOwnerAndId($this->user(), $id);
        $list->delete();

        return \Response::make(null, 204);
	}

}