<?php

namespace App\Http\Controllers\Admin;

use App\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreItemsRequest;
use App\Http\Requests\Admin\UpdateItemsRequest;
use Yajra\DataTables\DataTables;

class ItemsController extends Controller
{
    /**
     * Display a listing of Item.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (! Gate::allows('item_access')) {
            return abort(401);
        }


        
        if (request()->ajax()) {
            $query = Item::query();
            $query->with("survey");
            $query->with("question");
            $template = 'actionsTemplate';
            if(request('show_deleted') == 1) {
                
        if (! Gate::allows('item_delete')) {
            return abort(401);
        }
                $query->onlyTrashed();
                $template = 'restoreTemplate';
            }
            $query->select([
                'items.id',
                'items.survey_id',
                'items.question_id',
                'items.label',
                'items.order',
            ]);
            $table = Datatables::of($query);

            $table->setRowAttr([
                'data-entry-id' => '{{$id}}',
            ]);
            $table->addColumn('massDelete', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');
            $table->editColumn('actions', function ($row) use ($template) {
                $gateKey  = 'item_';
                $routeKey = 'admin.items';

                return view($template, compact('row', 'gateKey', 'routeKey'));
            });
            $table->editColumn('survey.title', function ($row) {
                return $row->survey ? $row->survey->title : '';
            });
            $table->editColumn('question.title', function ($row) {
                return $row->question ? $row->question->title : '';
            });
            $table->editColumn('label', function ($row) {
                return \Form::checkbox("label", 1, $row->label == 1, ["disabled"]);
            });
            $table->editColumn('order', function ($row) {
                return $row->order ? $row->order : '';
            });

            $table->rawColumns(['actions','massDelete','label']);

            return $table->make(true);
        }

        return view('admin.items.index');
    }

    /**
     * Show the form for creating new Item.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (! Gate::allows('item_create')) {
            return abort(401);
        }
        
        $surveys = \App\Survey::get()->pluck('title', 'id')->prepend(trans('quickadmin.qa_please_select'), '');
        $questions = \App\Question::get()->pluck('title', 'id')->prepend(trans('quickadmin.qa_please_select'), '');

        return view('admin.items.create', compact('surveys', 'questions'));
    }

    /**
     * Store a newly created Item in storage.
     *
     * @param  \App\Http\Requests\StoreItemsRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreItemsRequest $request)
    {
        if (! Gate::allows('item_create')) {
            return abort(401);
        }
        $item = Item::create($request->all());



        return redirect()->route('admin.items.index');
    }


    /**
     * Show the form for editing Item.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if (! Gate::allows('item_edit')) {
            return abort(401);
        }
        
        $surveys = \App\Survey::get()->pluck('title', 'id')->prepend(trans('quickadmin.qa_please_select'), '');
        $questions = \App\Question::get()->pluck('title', 'id')->prepend(trans('quickadmin.qa_please_select'), '');

        $item = Item::findOrFail($id);

        return view('admin.items.edit', compact('item', 'surveys', 'questions'));
    }

    /**
     * Update Item in storage.
     *
     * @param  \App\Http\Requests\UpdateItemsRequest  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateItemsRequest $request, $id)
    {
        if (! Gate::allows('item_edit')) {
            return abort(401);
        }
        $item = Item::findOrFail($id);
        $item->update($request->all());



        return redirect()->route('admin.items.index');
    }


    /**
     * Display Item.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        if (! Gate::allows('item_view')) {
            return abort(401);
        }
        $item = Item::findOrFail($id);

        return view('admin.items.show', compact('item'));
    }


    /**
     * Remove Item from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (! Gate::allows('item_delete')) {
            return abort(401);
        }
        $item = Item::findOrFail($id);
        $item->delete();

        return redirect()->route('admin.items.index');
    }

    /**
     * Delete all selected Item at once.
     *
     * @param Request $request
     */
    public function massDestroy(Request $request)
    {
        if (! Gate::allows('item_delete')) {
            return abort(401);
        }
        if ($request->input('ids')) {
            $entries = Item::whereIn('id', $request->input('ids'))->get();

            foreach ($entries as $entry) {
                $entry->delete();
            }
        }
    }


    /**
     * Restore Item from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function restore($id)
    {
        if (! Gate::allows('item_delete')) {
            return abort(401);
        }
        $item = Item::onlyTrashed()->findOrFail($id);
        $item->restore();

        return redirect()->route('admin.items.index');
    }

    /**
     * Permanently delete Item from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function perma_del($id)
    {
        if (! Gate::allows('item_delete')) {
            return abort(401);
        }
        $item = Item::onlyTrashed()->findOrFail($id);
        $item->forceDelete();

        return redirect()->route('admin.items.index');
    }
}
