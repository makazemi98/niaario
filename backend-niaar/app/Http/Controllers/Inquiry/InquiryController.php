<?php

namespace App\Http\Controllers\Inquiry;

use App\Http\Controllers\Controller;
use App\Http\Requests\Inquiry\AssignRequest;
use App\Http\Requests\Inquiry\PerformActionRequest;
use App\Http\Requests\Inquiry\StoreCommentRequest;
use App\Http\Requests\Inquiry\StoreInquiryRequest;
use App\Http\Resources\Comments\CommentResource;
use App\Http\Resources\Inquiry\InquiryCollection;
use App\Http\Resources\Inquiry\InquiryResource;
use App\Interfaces\Repositories\InquiryRepositoryInterface;
use App\Models\Inquiry;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class InquiryController extends Controller
{
    public function __construct(protected InquiryRepositoryInterface $inquiryRepository)
    {
        //
    }

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return InquiryCollection
     */
    public function index(Request $request): InquiryCollection
    {
        return new InquiryCollection($this->inquiryRepository->list($request->toArray()));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreInquiryRequest $request
     * @return InquiryResource | Response
     */
    public function store(StoreInquiryRequest $request)
    {
        $result = $this->inquiryRepository->storeInquiry($request->toArray());

        if ($result instanceof \Exception) {
            Log::channel('inquiries')
                ->error($result);

            return response()->json(
                [
                    'message' => __('messages.inquiries.create.failed')
                ],
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
        return ( new InquiryResource($result) )
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     *
     * @param Inquiry $inquiry
     * @return InquiryResource
     */
    public function show(Inquiry $inquiry)
    {
        $inquiry->load([
            'client',
            'assignedTo'
        ]);

        return new InquiryResource($inquiry);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    /**
     * Assign an inquiry to a user
     *
     * @param AssignRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function assign(Inquiry $inquiry, AssignRequest $request)
    {
        $result = $this->inquiryRepository
            ->assignTo($inquiry, $request->input('assign_to'));

        $message = trans('messages.inquiries.assign.success');
        $statusCode = Response::HTTP_ACCEPTED;

        if ($result instanceof \Exception) {
            Log::channel('inquiries')
                ->error($result);

            $message =trans('messages.inquiries.assign.failed');
            $statusCode =  Response::HTTP_INTERNAL_SERVER_ERROR;
        }

        return response()->json(['message' => $message], $statusCode);
    }

    /**
     * Get specified inquiry logs
     *
     * @param Inquiry $inquiry
     * @return InquiryResource
     */
    public function log(Inquiry $inquiry)
    {
        $inquiry->load([
            'comments.creator',
            'client',
            'assignedTo',
            'creator'
        ]);

        return new InquiryResource($inquiry);
    }

    /**
     * @param StoreCommentRequest $request
     * @param Inquiry $inquiry
     * @return CommentResource
     */
    public function storeComment(StoreCommentRequest $request, Inquiry $inquiry)
    {
        $result = $this->inquiryRepository->storeComment($inquiry, $request->all());

        if ($result instanceof \Exception) {
            return response()->json([
                'message' => 'Store comment error. Please try again.'
            ], 500);
        }

        return new CommentResource($result);
    }

    /**
     * Perform re_assign or cancel action on an inquiry
     *
     * @param Inquiry $inquiry
     * @param PerformActionRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function performAction(Inquiry $inquiry, PerformActionRequest $request)
    {
        $result = $this->inquiryRepository->performAction($inquiry, $request->all());

        $message = trans("messages.inquiries.actions.{$request->input('action')}.success");
        $statusCode =  Response::HTTP_ACCEPTED;

        if ($result instanceof \Exception) {
            Log::channel('inquiries')
                ->error($result);

            $message = trans("messages.inquiries.actions.{$request->input('action')}.failed");
            $statusCode =  Response::HTTP_INTERNAL_SERVER_ERROR;
        }

        return response()->json(['message' => $message], $statusCode);
    }
}
