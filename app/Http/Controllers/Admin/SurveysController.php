<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreSurveysRequest;
use App\Http\Requests\Admin\UpdateSurveysRequest;
use App\Item;
use App\Survey;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class SurveysController extends Controller
{
    /**
     * Display a listing of Survey.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (! Gate::allows('survey_access')) {
            return abort(401);
        }


        if (request('show_deleted') == 1) {
            if (! Gate::allows('survey_delete')) {
                return abort(401);
            }
            $surveys = Survey::onlyTrashed()->get();
        } else {
            $surveys = Survey::latest()->get();
        }

        return view('admin.surveys.index', compact('surveys'));
    }

    /**
     * Show the form for creating new Survey.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (! Gate::allows('survey_create')) {
            return abort(401);
        }
        
        $institutions = \App\Institution::latest()->get()->pluck('title', 'id')->prepend(trans('quickadmin.qa_please_select'), '');
        $categories = \App\Category::get()->pluck('title', 'id');

        $groups = \App\Group::get()->pluck('title', 'id');


        return view('admin.surveys.create', compact('institutions', 'categories', 'groups'));
    }

    /**
     * Store a newly created Survey in storage.
     *
     * @param  \App\Http\Requests\StoreSurveysRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreSurveysRequest $request)
    {
        if (! Gate::allows('survey_create')) {
            return abort(401);
        }
        $survey = Survey::create($request->all());
        $survey->category()->sync(array_filter((array)$request->input('category')));
        $survey->group()->sync(array_filter((array)$request->input('group')));



        return redirect()->route('admin.surveys.index');
    }


    /**
     * Show the form for editing Survey.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if (! Gate::allows('survey_edit')) {
            return abort(401);
        }
        
        $institutions = \App\Institution::get()->pluck('title', 'id')->prepend(trans('quickadmin.qa_please_select'), '');
        $categories = \App\Category::get()->pluck('title', 'id');

        $groups = \App\Group::get()->pluck('title', 'id');


        $survey = Survey::findOrFail($id);

        return view('admin.surveys.edit', compact('survey', 'institutions', 'categories', 'groups'));
    }

    /**
     * Update Survey in storage.
     *
     * @param  \App\Http\Requests\UpdateSurveysRequest  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateSurveysRequest $request, $id)
    {
        if (! Gate::allows('survey_edit')) {
            return abort(401);
        }
        $survey = Survey::findOrFail($id);
        $survey->update($request->all());
        $survey->category()->sync(array_filter((array)$request->input('category')));
        $survey->group()->sync(array_filter((array)$request->input('group')));



        return redirect()->route('admin.surveys.index');
    }


    /**
     * Display Survey.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        if (! Gate::allows('survey_view')) {
            return abort(401);
        }
        
        $survey = Survey::findOrFail($id);
        $questionnaires = \App\Questionnaire::where('survey_id', $id)->latest()->get();
        $items = \App\Item::where('survey_id', $id)->orderBy('order')->get();

        $duplicates = $this->get_duplicates($id);

        return view('admin.surveys.show', compact('survey', 'questionnaires', 'items', 'duplicates'));
        }


    /**
     * Remove Survey from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (! Gate::allows('survey_delete')) {
            return abort(401);
        }
        $survey = Survey::findOrFail($id);
        $survey->delete();

        return redirect()->route('admin.surveys.index');
    }

    /**
     * Delete all selected Survey at once.
     *
     * @param Request $request
     */
    public function massDestroy(Request $request)
    {
        if (! Gate::allows('survey_delete')) {
            return abort(401);
        }
        if ($request->input('ids')) {
            $entries = Survey::whereIn('id', $request->input('ids'))->get();

            foreach ($entries as $entry) {
                $entry->delete();
            }
        }
    }


    /**
     * Restore Survey from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function restore($id)
    {
        if (! Gate::allows('survey_delete')) {
            return abort(401);
        }
        $survey = Survey::onlyTrashed()->findOrFail($id);
        $survey->restore();

        return redirect()->route('admin.surveys.index');
    }

    /**
     * Permanently delete Survey from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function perma_del($id)
    {
        if (! Gate::allows('survey_delete')) {
            return abort(401);
        }
        $survey = Survey::onlyTrashed()->findOrFail($id);
        $survey->forceDelete();

        return redirect()->route('admin.surveys.index');
    }


    /**
     * clone Survey
     *
     * @param  App\Survey  $survey
     * @return \Illuminate\Http\Response
     */
    public function clone(Survey $survey)
    {
        if (! Gate::allows('survey_create')) {
            return abort(401);
        }

        $newSurvey = $survey->replicate();
        $newSurvey->completed = 0;
        $newSurvey->push(); //Push before to get id of $clone

        foreach(Item::where('survey_id',$survey->id)->get() as $item) {
            $newItem = $item->replicate();
            $newItem->survey_id = $newSurvey->id;
            $newItem->save();
        }

        foreach ($survey->category as $category) {
            $newSurvey->category()->attach($category);
        }

        foreach ($survey->group as $group) {
            $newSurvey->group()->attach($group);
        }

        return redirect()->route('admin.surveys.show',$newSurvey);
    }


    protected function get_duplicates($survey_id){
        /** get duplicates */
        $loguseragent = new \App\Loguseragent();
        $duplicates = [];
        
        /** get $survey->questionnaires */
        $questionnaires_arr = \App\Questionnaire::where('survey_id', $survey_id)->latest()->get()->pluck('id');

        /** select by ip and sw */
        $duplicate_ipsw = $loguseragent::selectRaw('`ipv6`, `os`, `os_version`, `browser`, `browser_version`, COUNT(*) as `count` ')
            ->whereIn('item_id', $questionnaires_arr)
            ->groupBy('ipv6', 'os', 'os_version', 'browser', 'browser_version')
            ->having('count', '>', 1)
            ->get();

        /** select by ip */
        $duplicate_ip = $loguseragent::selectRaw('`ipv6`, COUNT(*) as `count` ')
            ->whereIn('item_id', $questionnaires_arr)
            ->groupBy('ipv6')
            ->having('count', '>', 1)
            ->get();

        /** select by sw */
        $duplicate_sw = $loguseragent::selectRaw('`os`, `os_version`, `browser`, `browser_version`, COUNT(*) as `count` ')
            ->whereIn('item_id', $questionnaires_arr)
            ->groupBy('os', 'os_version', 'browser', 'browser_version')
            ->having('count', '>', 1)
            ->get();


        foreach ($duplicate_ipsw as $obj) {
            $row = [];
            $row['type'] = 'ipsw';
            $row['value'] = ['ipv6'=>$obj->ipv6, 'os'=>$obj->os, 'os_version'=>$obj->os_version, 'browser' => $obj->browser, 'browser_version'=>$obj->browser_version];
            $row['count'] = $obj->count;
            $row['loguseragents'] = $loguseragent->whereIn('item_id', $questionnaires_arr)->where([['ipv6',$obj->ipv6], ['os',$obj->os], ['os_version',$obj->os_version], ['browser',$obj->browser], ['browser_version',$obj->browser_version]])->get();
            // remove results from questionnaires list @todo
            // $questionnaires_arr = array_diff($questionnaires_arr,$row['loguseragents']->pluck('item_id'));
            $duplicates[]=$row;
         }

        foreach ($duplicate_ip as $obj) {
            $row = [];
            $row['type'] = 'ip';
            $row['value'] = $obj->ipv6;
            $row['count'] = $obj->count;
            $row['loguseragents'] = $loguseragent->whereIn('item_id', $questionnaires_arr)->where('ipv6',$obj->ipv6)->get();
            $duplicates[]=$row;
         }

        foreach ($duplicate_sw as $obj) {
            $row = [];
            $row['type'] = 'sw';
            $row['value'] = ['os'=>$obj->os, 'os_version'=>$obj->os_version, 'browser' => $obj->browser, 'browser_version'=>$obj->browser_version];
            $row['count'] = $obj->count;
            $row['loguseragents'] = $loguseragent->whereIn('item_id', $questionnaires_arr)->where([['os',$obj->os], ['os_version',$obj->os_version], ['browser',$obj->browser], ['browser_version',$obj->browser_version]])->get();
            $duplicates[]=$row;
         }

        return $duplicates;

    }

}
