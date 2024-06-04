<?php

namespace App\Http\Controllers\Web\Awesome_Admin;

use App\Enums\QueryAcceptedComparatorEnum;
use App\Http\Controllers\Api\BaseApiController;
use App\Http\Controllers\Controller;
use App\Http\Requests\Account\UpdateAccountRequest;
use App\Http\Resources\User\UserListResource;
use App\Http\Resources\User\UserProfileResource;
use App\Models\Account;
use App\Models\BlogArticle;
use App\Services\FormBuilderService;
use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class Awesome_Admin_FormBuilderController extends Controller
{
    public function __construct(
        public FormBuilderService $formBuilderService
    ) 
    {
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // $test = Schema::getColumns((new Account())->getTable());
        // dd($test);
        return view('builder', array(
            'models' => $this->formBuilderService->getModels(app_path('Models')))
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

    }

    /**
     * Display the specified resource.
     */
    public function show(string $idOrSlug)
    {
        try {
            $api = new BaseApiController();
            $data = $this->userService->findById($idOrSlug);
            throw_if(empty($data), new NotFoundHttpException(404));

            $data = (new UserProfileResource($data));
            
            $response = $api->setStatusMsg("success")->respondOK(array(
                'data' => $data
            ));
        } catch (NotFoundHttpException $th) {
            $response = $api->setStatusMsg("failed")->respondForbidden();
        } catch (\Throwable $th) {
            $response = $api->setStatusMsg("failed")->respondInternalError(null, $th->getMessage());
        } finally {
            return $response;
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $idOrSlug)
    {
        $data = $this->userService->findById($idOrSlug);
        // $data = BlogArticle::find(1);

        if (!$data) {
            return redirect()->back()->with("error", "not found");
        }

        return view('form', [
            'data' => $data, 
            'urls' => $this->userService->getUrls(),
            'editMode' => true
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateAccountRequest $request, string $idOrSlug)
    {
        try {
            $user = $this->userService->update($request->all(), $idOrSlug);

            if ($request->wantsJson()) {
                $response = response()->json([
                    'success' => true,
                    'message' => "Successfully updated user {$user->username}",
                ]);
            } else {
                $response = redirect()
                    ->back()
                    ->with('success', "Successfully updated user {$user->username}");
            }
        } catch (\Throwable $th) {
            if ($request->wantsJson()) {
                $response = response()->json([
                    'success' => false,
                    'message' => $th->getMessage(),
                ], 500);
            } else {
                $response = redirect()
                    ->back()
                    ->withInput()
                    ->with('error', $th->getMessage());
            }
        } finally {
            return $response;
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

     /**
     * Get column of a model
     */
    public function getModelColumns(Request $request)
    {
        try {
            $api = new BaseApiController();
            $model = $request->get('model');
            $data = Schema::getColumns((new $model)->getTable());
            
            $response = $api->setStatusMsg("success")->respondOK(array(
                'data' => $data
            ));
        } catch (NotFoundHttpException $th) {
            $response = $api->setStatusMsg("failed")->respondForbidden();
        } catch (\Throwable $th) {
            $response = $api->setStatusMsg("failed")->respondInternalError(null, $th->getMessage());
        } finally {
            return $response;
        }
        
    }
}
