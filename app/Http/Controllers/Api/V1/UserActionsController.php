<?php

namespace App\Http\Controllers\Api\V1;

use App\UserAction;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserAction as UserActionResource;
use App\Http\Requests\Admin\StoreUserActionsRequest;
use App\Http\Requests\Admin\UpdateUserActionsRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;



class UserActionsController extends Controller
{
    public function index()
    {
        

        return new UserActionResource(UserAction::with(['user'])->get());
    }

    public function show($id)
    {
        if (Gate::denies('user_action_view')) {
            return abort(401);
        }

        $user_action = UserAction::with(['user'])->findOrFail($id);

        return new UserActionResource($user_action);
    }

    public function store(StoreUserActionsRequest $request)
    {
        if (Gate::denies('user_action_create')) {
            return abort(401);
        }

        $user_action = UserAction::create($request->all());
        
        

        return (new UserActionResource($user_action))
            ->response()
            ->setStatusCode(201);
    }

    public function update(UpdateUserActionsRequest $request, $id)
    {
        if (Gate::denies('user_action_edit')) {
            return abort(401);
        }

        $user_action = UserAction::findOrFail($id);
        $user_action->update($request->all());
        
        
        

        return (new UserActionResource($user_action))
            ->response()
            ->setStatusCode(202);
    }

    public function destroy($id)
    {
        if (Gate::denies('user_action_delete')) {
            return abort(401);
        }

        $user_action = UserAction::findOrFail($id);
        $user_action->delete();

        return response(null, 204);
    }
}
