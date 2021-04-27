<?php

namespace App\Http\Controllers\Admin;

use App\Activitylog;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreActivitylogsRequest;
use App\Http\Requests\Admin\UpdateActivitylogsRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Yajra\DataTables\DataTables;

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

        if (request()->ajax()) {
            $query = Activitylog::query();
            $template = 'actionsTemplate';

            $query->select([
                'activity_log.id',
                'activity_log.log_name',
                'activity_log.causer_type',
                'activity_log.causer_id',
                'activity_log.description',
                'activity_log.subject_type',
                'activity_log.subject_id',
                'activity_log.properties',
            ]);
            $table = Datatables::of($query);

            $table->setRowAttr([
                'data-entry-id' => '{{$id}}',
            ]);
            $table->addColumn('massDelete', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');
            $table->editColumn('actions', function ($row) use ($template) {
                $gateKey = 'activitylog_';
                $routeKey = 'admin.activitylogs';

                return view($template, compact('row', 'gateKey', 'routeKey'));
            });
            $table->editColumn('log_name', function ($row) {
                return $row->log_name ? $row->log_name : '';
            });
            $table->editColumn('causer_type', function ($row) {
                return $row->causer_type ? $row->causer_type : '';
            });
            $table->editColumn('causer_id', function ($row) {
                return $row->causer_id ? $row->causer_id : '';
            });
            $table->editColumn('description', function ($row) {
                return $row->description ? $row->description : '';
            });
            $table->editColumn('subject_type', function ($row) {
                return $row->subject_type ? $row->subject_type : '';
            });
            $table->editColumn('subject_id', function ($row) {
                return $row->subject_id ? $row->subject_id : '';
            });
            $table->editColumn('properties', function ($row) {
                return $row->properties ? $row->properties : '';
            });

            $table->rawColumns(['actions', 'massDelete']);

            return $table->make(true);
        }

        return view('admin.activitylogs.index');
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
