<?php

namespace App\Http\Controllers\Accounting;

use App\Enums\AccountingPagesEnum;
use App\Enums\PaymentTabsEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\Payment\StorePaymentRequest;
use App\Http\Resources\Inquiry\InquiryCollection;
use App\Http\Resources\Payment\BalanceSheetResource;
use App\Http\Resources\Payment\PaymentCollection;
use App\Http\Resources\Payment\PaymentResource;
use App\Interfaces\Repositories\InquiryRepositoryInterface;
use App\Interfaces\Repositories\PaymentRepositoryInterface;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class AccountingController extends Controller
{
    public function __construct(
        protected PaymentRepositoryInterface $paymentRepository,
        protected InquiryRepositoryInterface $inquiryRepository
    )
    {
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse|PaymentCollection|InquiryCollection
     */
    public function index(Request $request, $tab_name)
    {
        if ($tab_name == 'meta') {
            return $this->paymentRepository->setFilters($request->all())->getMeta();
        } elseif (
            in_array($tab_name, PaymentTabsEnum::values()) &&
            $tab_name !== PaymentTabsEnum::PROFIT->value
        ) {
            $result = $this->paymentRepository
                ->setTabName($tab_name)
                ->setFilters($request->all())
                ->getTabData();

            if ($result->isNotEmpty()) {
                if ($tab_name == PaymentTabsEnum::BALANCE_SHEET->value) {
                    $collection = BalanceSheetResource::collection($result);
                } else {
                    $collection = ( new PaymentCollection($result) );
                }

                return $collection
                    ->additional([
                        'totals' => [
                            'debit' => $result->sum('debit'),
                            'credit' => $result->sum('credit'),
                            'balance' => $result->sum('balance')
                        ]
                    ]);
            } else {
                return response()->json([
                    'data' => null,
                    'totals' => [
                        'debit' => 0,
                        'credit' => 0,
                        'balance' => 0
                    ]
                ]);
            }
        } elseif ($tab_name == PaymentTabsEnum::PROFIT->value) {
            $result = $this->inquiryRepository->list($request->all());

            if ($result->isNotEmpty()) {
                return ( new InquiryCollection($result) )
                    ->additional([
                        'totals' => [
                            'debit' => $result->sum('debit'),
                            'credit' => $result->sum('credit'),
                            'balance' => $result->sum('balance')
                        ]
                    ]);
            } else {
                return response()->json(['data' => null]);
            }
        } else {
            abort(Response::HTTP_NOT_FOUND, "Invalid tab name: {$request->segment(5)}");
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param Request $request
     * @param Payment $payment
     * @return \Illuminate\Http\JsonResponse
     */
    public function changePaidStatus(Request $request, Payment $payment): \Illuminate\Http\JsonResponse
    {
        abort_if(
            $payment->tab !== PaymentTabsEnum::FUTURE_DUES->value,
            Response::HTTP_BAD_REQUEST,
            'Invalid future due id'
        );

        $request->validate([
            'is_paid' => ['required', 'boolean']
        ]);

        $payment->update([
            'is_paid' => $request->input('is_paid')
        ]);

        $payment->load('inquiry');

        return ( new PaymentResource($payment) )
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StorePaymentRequest $request
     * @return BalanceSheetResource|PaymentResource
     */
    public function store(StorePaymentRequest $request)
    {
        $tab = $request->segment(5);

        $request->merge([
            'created_by' => Auth::id(),
            'tab' => $tab
        ]);

        $resource = $this->paymentRepository->store($request->all());

        if ($tab == PaymentTabsEnum::BALANCE_SHEET->value) {
            return BalanceSheetResource::make($resource);
        }

        return PaymentResource::make($resource);
    }

    /**
     * Display the specified resource.
     *
     * @param $meta
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getByMeta($meta, Request $request)
    {
        abort_if(!in_array($meta, AccountingPagesEnum::values()), 404);

        return response()->json([
            'data' => $this->paymentRepository->setFilters($request->all())->getUserByMeta($meta)
        ]);
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
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
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
