<?php

namespace App\Http\Controllers\Admin;

use App\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreItemsRequest;
use App\Http\Requests\Admin\UpdateItemsRequest;

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


        if (request('show_deleted') == 1) {
            if (! Gate::allows('item_delete')) {
                return abort(401);
            }
            $items = Item::onlyTrashed()->get();
        } else {
            $items = Item::all();
        }

        return view('admin.items.index', compact('items'));
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
