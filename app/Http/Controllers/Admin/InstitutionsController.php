<?php

namespace App\Http\Controllers\Admin;

use App\Institution;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreInstitutionsRequest;
use App\Http\Requests\Admin\UpdateInstitutionsRequest;

class InstitutionsController extends Controller
{
    /**
     * Display a listing of Institution.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (! Gate::allows('institution_access')) {
            return abort(401);
        }


        if (request('show_deleted') == 1) {
            if (! Gate::allows('institution_delete')) {
                return abort(401);
            }
            $institutions = Institution::onlyTrashed()->get();
        } else {
            $institutions = Institution::all();
        }

        return view('admin.institutions.index', compact('institutions'));
    }

    /**
     * Show the form for creating new Institution.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (! Gate::allows('institution_create')) {
            return abort(401);
        }
        return view('admin.institutions.create');
    }

    /**
     * Store a newly created Institution in storage.
     *
     * @param  \App\Http\Requests\StoreInstitutionsRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreInstitutionsRequest $request)
    {
        if (! Gate::allows('institution_create')) {
            return abort(401);
        }
        $institution = Institution::create($request->all());



        return redirect()->route('admin.institutions.index');
    }


    /**
     * Show the form for editing Institution.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if (! Gate::allows('institution_edit')) {
            return abort(401);
        }
        $institution = Institution::findOrFail($id);

        return view('admin.institutions.edit', compact('institution'));
    }

    /**
     * Update Institution in storage.
     *
     * @param  \App\Http\Requests\UpdateInstitutionsRequest  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateInstitutionsRequest $request, $id)
    {
        if (! Gate::allows('institution_edit')) {
            return abort(401);
        }
        $institution = Institution::findOrFail($id);
        $institution->update($request->all());



        return redirect()->route('admin.institutions.index');
    }


    /**
     * Display Institution.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        if (! Gate::allows('institution_view')) {
            return abort(401);
        }
        $institution = Institution::findOrFail($id);

        return view('admin.institutions.show', compact('institution'));
    }


    /**
     * Remove Institution from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (! Gate::allows('institution_delete')) {
            return abort(401);
        }
        $institution = Institution::findOrFail($id);
        $institution->delete();

        return redirect()->route('admin.institutions.index');
    }

    /**
     * Delete all selected Institution at once.
     *
     * @param Request $request
     */
    public function massDestroy(Request $request)
    {
        if (! Gate::allows('institution_delete')) {
            return abort(401);
        }
        if ($request->input('ids')) {
            $entries = Institution::whereIn('id', $request->input('ids'))->get();

            foreach ($entries as $entry) {
                $entry->delete();
            }
        }
    }


    /**
     * Restore Institution from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function restore($id)
    {
        if (! Gate::allows('institution_delete')) {
            return abort(401);
        }
        $institution = Institution::onlyTrashed()->findOrFail($id);
        $institution->restore();

        return redirect()->route('admin.institutions.index');
    }

    /**
     * Permanently delete Institution from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function perma_del($id)
    {
        if (! Gate::allows('institution_delete')) {
            return abort(401);
        }
        $institution = Institution::onlyTrashed()->findOrFail($id);
        $institution->forceDelete();

        return redirect()->route('admin.institutions.index');
    }
}
