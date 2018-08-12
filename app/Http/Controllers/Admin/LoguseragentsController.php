<?php

namespace App\Http\Controllers\Admin;

use App\Loguseragent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreLoguseragentsRequest;
use App\Http\Requests\Admin\UpdateLoguseragentsRequest;
use Yajra\DataTables\DataTables;

class LoguseragentsController extends Controller
{
    /**
     * Display a listing of Loguseragent.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (! Gate::allows('loguseragent_access')) {
            return abort(401);
        }


        
        if (request()->ajax()) {
            $query = Loguseragent::query();
            $query->with("user");
            $template = 'actionsTemplate';
            
            $query->select([
                'loguseragents.id',
                'loguseragents.os',
                'loguseragents.os_version',
                'loguseragents.browser',
                'loguseragents.browser_version',
                'loguseragents.device',
                'loguseragents.language',
                'loguseragents.item_id',
                'loguseragents.ipv6',
                'loguseragents.uri',
                'loguseragents.form_submitted',
                'loguseragents.user_id',
            ]);
            $table = Datatables::of($query);

            $table->setRowAttr([
                'data-entry-id' => '{{$id}}',
            ]);
            $table->addColumn('massDelete', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');
            $table->editColumn('actions', function ($row) use ($template) {
                $gateKey  = 'loguseragent_';
                $routeKey = 'admin.loguseragents';

                return view($template, compact('row', 'gateKey', 'routeKey'));
            });
            $table->editColumn('os', function ($row) {
                return $row->os ? $row->os : '';
            });
            $table->editColumn('os_version', function ($row) {
                return $row->os_version ? $row->os_version : '';
            });
            $table->editColumn('browser', function ($row) {
                return $row->browser ? $row->browser : '';
            });
            $table->editColumn('browser_version', function ($row) {
                return $row->browser_version ? $row->browser_version : '';
            });
            $table->editColumn('device', function ($row) {
                return $row->device ? $row->device : '';
            });
            $table->editColumn('language', function ($row) {
                return $row->language ? $row->language : '';
            });
            $table->editColumn('item_id', function ($row) {
                return $row->item_id ? $row->item_id : '';
            });
            $table->editColumn('ipv6', function ($row) {
                return $row->ipv6 ? $row->ipv6 : '';
            });
            $table->editColumn('uri', function ($row) {
                return $row->uri ? $row->uri : '';
            });
            $table->editColumn('form_submitted', function ($row) {
                return \Form::checkbox("form_submitted", 1, $row->form_submitted == 1, ["disabled"]);
            });
            $table->editColumn('user.name', function ($row) {
                return $row->user ? $row->user->name : '';
            });

            $table->rawColumns(['actions','massDelete','form_submitted']);

            return $table->make(true);
        }

        return view('admin.loguseragents.index');
    }

    /**
     * Show the form for editing Loguseragent.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if (! Gate::allows('loguseragent_edit')) {
            return abort(401);
        }
        
        $users = \App\User::get()->pluck('name', 'id')->prepend(trans('quickadmin.qa_please_select'), '');

        $loguseragent = Loguseragent::findOrFail($id);

        return view('admin.loguseragents.edit', compact('loguseragent', 'users'));
    }

    /**
     * Update Loguseragent in storage.
     *
     * @param  \App\Http\Requests\UpdateLoguseragentsRequest  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateLoguseragentsRequest $request, $id)
    {
        if (! Gate::allows('loguseragent_edit')) {
            return abort(401);
        }
        $loguseragent = Loguseragent::findOrFail($id);
        $loguseragent->update($request->all());



        return redirect()->route('admin.loguseragents.index');
    }


    /**
     * Display Loguseragent.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        if (! Gate::allows('loguseragent_view')) {
            return abort(401);
        }
        $loguseragent = Loguseragent::findOrFail($id);

        return view('admin.loguseragents.show', compact('loguseragent'));
    }


    /**
     * Remove Loguseragent from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (! Gate::allows('loguseragent_delete')) {
            return abort(401);
        }
        $loguseragent = Loguseragent::findOrFail($id);
        $loguseragent->delete();

        return redirect()->route('admin.loguseragents.index');
    }

    /**
     * Delete all selected Loguseragent at once.
     *
     * @param Request $request
     */
    public function massDestroy(Request $request)
    {
        if (! Gate::allows('loguseragent_delete')) {
            return abort(401);
        }
        if ($request->input('ids')) {
            $entries = Loguseragent::whereIn('id', $request->input('ids'))->get();

            foreach ($entries as $entry) {
                $entry->delete();
            }
        }
    }

}
