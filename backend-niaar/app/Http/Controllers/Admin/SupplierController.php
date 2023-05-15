<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Supplier\StoreSupplierRequest;
use App\Http\Requests\Supplier\UpdateSupplierRequest;
use App\Http\Resources\Supplier\SupplierCollection;
use App\Http\Resources\Supplier\SupplierResource;
use App\Interfaces\Repositories\SupplierRepositoryInterface;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class SupplierController extends Controller
{
    public function __construct(protected SupplierRepositoryInterface $supplierRepository)
    {
    }

    /**
     * Display a listing of the resource.
     *
     * @return SupplierCollection
     */
    public function index(Request $request)
    {
        return new SupplierCollection($this->supplierRepository->list($request->all()));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreSupplierRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(StoreSupplierRequest $request)
    {
        $result = $this->supplierRepository->storeSupplier($request->all());

        $message = trans('messages.suppliers.create.success');

        $statusCode = Response::HTTP_CREATED;

        if ($result instanceof \Exception) {
            Log::channel('suppliers')
                ->error($result);

            $message = trans('messages.suppliers.create.failed');

            $statusCode = Response::HTTP_INTERNAL_SERVER_ERROR;

            return response()->json(
                [
                    'message' => $message
                ],
                $statusCode
            );

        }

        return ( new SupplierResource($result) )
            ->additional([
                'message' => $message
            ])
            ->response()
            ->setStatusCode($statusCode);
    }

    /**
     * Display the specified resource.
     *
     * @param Supplier $supplier
     * @return SupplierResource
     */
    public function show(Supplier $supplier)
    {
        $supplier->load([
            'supplyingBrands',
            'productCategories',
            'personsInCharge',
            'customers',
        ]);

        return new SupplierResource($supplier);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateSupplierRequest $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(UpdateSupplierRequest $request, Supplier $supplier)
    {
        $result = $this->supplierRepository->updateSupplier($supplier, $request->all());

        $message = trans('messages.suppliers.update.success');

        $statusCode = Response::HTTP_ACCEPTED;

        if ($result instanceof \Exception) {
            Log::channel('suppliers')
                ->error($result);

            $message = trans('messages.suppliers.update.failed');

            $statusCode = Response::HTTP_INTERNAL_SERVER_ERROR;

            return response()->json(
                [
                    'message' => $message
                ],
                $statusCode
            );

        }

        return ( new SupplierResource($result) )
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
