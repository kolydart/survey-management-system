<?php

namespace App\Http\Controllers\Admin;

use App\Activitylog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreActivitylogsRequest;
use App\Http\Requests\Admin\UpdateActivitylogsRequest;

class ActivitylogsController extends Controller
{
    /**
     * Display a listing of Activitylog.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (! Gate::allows('activitylog_access')) {
            return abort(401);
        }


                $activitylogs = Activitylog::all();

        return view('admin.activitylogs.index', compact('activitylogs'));
    }

    /**
     * Show the form for editing Activitylog.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if (! Gate::allows('activitylog_edit')) {
            return abort(401);
        }
        $activitylog = Activitylog::findOrFail($id);

        return view('admin.activitylogs.edit', compact('activitylog'));
    }

    /**
     * Update Activitylog in storage.
     *
     * @param  \App\Http\Requests\UpdateActivitylogsRequest  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateActivitylogsRequest $request, $id)
    {
        if (! Gate::allows('activitylog_edit')) {
            return abort(401);
        }
        $activitylog = Activitylog::findOrFail($id);
        $activitylog->update($request->all());



        return redirect()->route('admin.activitylogs.index');
    }


    /**
     * Display Activitylog.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        if (! Gate::allows('activitylog_view')) {
            return abort(401);
        }
        $activitylog = Activitylog::findOrFail($id);

        return view('admin.activitylogs.show', compact('activitylog'));
    }


    /**
     * Remove Activitylog from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (! Gate::allows('activitylog_delete')) {
            return abort(401);
        }
        $activitylog = Activitylog::findOrFail($id);
        $activitylog->delete();

        return redirect()->route('admin.activitylogs.index');
    }

    /**
     * Delete all selected Activitylog at once.
     *
     * @param Request $request
     */
    public function massDestroy(Request $request)
    {
        if (! Gate::allows('activitylog_delete')) {
            return abort(401);
        }
        if ($request->input('ids')) {
            $entries = Activitylog::whereIn('id', $request->input('ids'))->get();

            foreach ($entries as $entry) {
                $entry->delete();
            }
        }
    }

}
