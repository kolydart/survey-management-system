<?php

namespace App\Http\Controllers\Admin;

use App\Group;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreGroupsRequest;
use App\Http\Requests\Admin\UpdateGroupsRequest;

class GroupsController extends Controller
{
    /**
     * Display a listing of Group.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (! Gate::allows('group_access')) {
            return abort(401);
        }


        if (request('show_deleted') == 1) {
            if (! Gate::allows('group_delete')) {
                return abort(401);
            }
            $groups = Group::onlyTrashed()->get();
        } else {
            $groups = Group::all();
        }

        return view('admin.groups.index', compact('groups'));
    }

    /**
     * Show the form for creating new Group.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (! Gate::allows('group_create')) {
            return abort(401);
        }
        return view('admin.groups.create');
    }

    /**
     * Store a newly created Group in storage.
     *
     * @param  \App\Http\Requests\StoreGroupsRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreGroupsRequest $request)
    {
        if (! Gate::allows('group_create')) {
            return abort(401);
        }
        $group = Group::create($request->all());



        return redirect()->route('admin.groups.index');
    }


    /**
     * Show the form for editing Group.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if (! Gate::allows('group_edit')) {
            return abort(401);
        }
        $group = Group::findOrFail($id);

        return view('admin.groups.edit', compact('group'));
    }

    /**
     * Update Group in storage.
     *
     * @param  \App\Http\Requests\UpdateGroupsRequest  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateGroupsRequest $request, $id)
    {
        if (! Gate::allows('group_edit')) {
            return abort(401);
        }
        $group = Group::findOrFail($id);
        $group->update($request->all());



        return redirect()->route('admin.groups.show',$id);
    }


    /**
     * Display Group.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        if (! Gate::allows('group_view')) {
            return abort(401);
        }
        $surveys = \App\Survey::whereHas('group',
                    function ($query) use ($id) {
                        $query->where('id', $id);
                    })->get();

        $group = Group::findOrFail($id);

        return view('admin.groups.show', compact('group', 'surveys'));
    }


    /**
     * Remove Group from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (! Gate::allows('group_delete')) {
            return abort(401);
        }
        $group = Group::findOrFail($id);
        $group->delete();

        return redirect()->route('admin.groups.index');
    }

    /**
     * Delete all selected Group at once.
     *
     * @param Request $request
     */
    public function massDestroy(Request $request)
    {
        if (! Gate::allows('group_delete')) {
            return abort(401);
        }
        if ($request->input('ids')) {
            $entries = Group::whereIn('id', $request->input('ids'))->get();

            foreach ($entries as $entry) {
                $entry->delete();
            }
        }
    }


    /**
     * Restore Group from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function restore($id)
    {
        if (! Gate::allows('group_delete')) {
            return abort(401);
        }
        $group = Group::onlyTrashed()->findOrFail($id);
        $group->restore();

        return redirect()->route('admin.groups.index');
    }

    /**
     * Permanently delete Group from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function perma_del($id)
    {
        if (! Gate::allows('group_delete')) {
            return abort(401);
        }
        $group = Group::onlyTrashed()->findOrFail($id);
        $group->forceDelete();

        return redirect()->route('admin.groups.index');
    }
}
