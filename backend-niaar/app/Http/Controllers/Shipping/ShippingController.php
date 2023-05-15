<?php

namespace App\Http\Controllers\Shipping;

use App\Http\Controllers\Controller;
use App\Http\Requests\Shipping\StoreShippingRequest;
use App\Http\Requests\Shipping\UpdateShippingRequest;
use App\Http\Resources\Shipping\ShippingCollection;
use App\Http\Resources\Shipping\ShippingResource;
use App\Interfaces\Repositories\ShippingRepositoryInterface;
use App\Models\Shipping;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class ShippingController extends Controller
{
    public function __construct(protected ShippingRepositoryInterface $shippingRepository)
    {
    }

    /**
     * Display a listing of the resource.
     *
     * @return ShippingCollection
     */
    public function index(Request $request)
    {
        $shippings = $this->shippingRepository->list($request->toArray());

        return new ShippingCollection($shippings);
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
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(StoreShippingRequest $request)
    {
        $result = $this->shippingRepository->storeShipping($request->all());

        if ($result instanceof \Exception) {
            return response()->json(
                [
                    'message' => trans('messages.shipping.store.failed')
                ],
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }

        return ( new ShippingResource($result) )
            ->additional([
                'message' => trans('messages.shipping.store.success')
            ])
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     *
     * @param Shipping $shipping
     * @return ShippingResource
     */
    public function show(Shipping $shipping)
    {
        $shipping->load([
                'boxes.client:id,first_name,last_name',
                'boxes.creator:id,first_name,last_name',
                'comments'
            ])
            ->loadCount('boxes');

        return new ShippingResource($shipping);
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
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(UpdateShippingRequest $request, Shipping $shipping)
    {
        $result = $this->shippingRepository->updateShipping($shipping, $request->all());

        $message = trans('messages.shipping.update.success');

        $statusCode = Response::HTTP_ACCEPTED;

        if ($result instanceof \Exception) {
            $message = trans('messages.shipping.update.failed');

            $statusCode = Response::HTTP_INTERNAL_SERVER_ERROR;
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
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
