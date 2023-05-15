<?php

namespace App\Http\Controllers\Shipping;

use App\Http\Controllers\Controller;
use App\Http\Requests\Box\StoreBoxRequest;
use App\Http\Requests\Box\UpdateBoxRequest;
use App\Http\Resources\Shipping\ShippingResource;
use App\Models\Box;
use App\Models\Shipping;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class BoxController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreBoxRequest $request
     * @param Shipping $shipping
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(StoreBoxRequest $request, Shipping $shipping)
    {
        try {
            $boxData = $request->all();

            $shipping->boxes()->create([
                'client_id' => $boxData['client_id'],
                'inquiry_id' => $boxData['inquiry_id'],
                'sign' => $boxData['sign'],
                'content' => $boxData['content'],
                'height' => $boxData['height'],
                'length' => $boxData['length'],
                'width' => $boxData['width'],
                'volume' => $boxData['volume'],
                'created_by' => Auth::id()
            ]);

            $statusCode = Response::HTTP_CREATED;

            $message = trans('messages.shipping.boxes.store.success');

            $shipping->load(['boxes', 'comments.creator'])
                ->loadCount('boxes as total_boxes')
                ->loadSum('boxes as total_volume', 'volume');

        } catch (\Exception $exception) {
            Log::channel('shipping')->error($exception);

            $statusCode = Response::HTTP_INTERNAL_SERVER_ERROR;

            $message = trans('messages.shipping.boxes.store.failed');
        }

        return ( new ShippingResource($shipping) )
            ->additional([
                'message' => $message
            ])
            ->response()
            ->setStatusCode($statusCode);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateBoxRequest $request
     * @param Shipping $shipping
     * @param Box $box
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(UpdateBoxRequest $request, Shipping $shipping, Box $box)
    {
        try {
            $box->update($request->all());

            $statusCode = Response::HTTP_ACCEPTED;

            $message = trans('messages.shipping.boxes.update.success');

            $shipping->load(['boxes', 'comments.creator'])
                ->loadCount('boxes as total_boxes')
                ->loadSum('boxes as total_volume', 'volume');
        } catch (\Exception $exception) {
            Log::channel('shipping')->error($exception);

            $statusCode = Response::HTTP_INTERNAL_SERVER_ERROR;

            $message = trans('messages.shipping.boxes.update.failed');
        }

        return ( new ShippingResource($shipping) )
            ->additional([
                'message' => $message
            ])
            ->response()
            ->setStatusCode($statusCode);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Shipping $shipping, Box $box)
    {
        abort_if($box->shipping_id !== $shipping->id, Response::HTTP_FORBIDDEN);

        $box->delete();

        return response()->json(
            [
                'message' => trans('messages.shipping.boxes.delete.success')
            ],
            Response::HTTP_OK
        );
    }
}
