<?php

namespace App\Http\Controllers\User;

use App\Enums\InquiryStatusesEnum;
use App\Enums\PaymentTabsEnum;
use App\Enums\RolesEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\User\StoreUserRequest;
use App\Http\Requests\User\UpdateUserRequest;
use App\Http\Resources\User\UserCollection;
use App\Http\Resources\User\UserResource;
use App\Interfaces\Repositories\UserRepositoryInterface;
use App\Models\Inquiry;
use App\Models\Payment;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;
use function __;
use function response;
use function trans;

class UserController extends Controller
{

    public function __construct(protected UserRepositoryInterface $userRepository)
    {
        $this->setDestroyMiddleware();
    }

    protected function setDestroyMiddleware()
    {
        $canDeleteUser = implode('|', [
            RolesEnum::MANAGER->value,
            RolesEnum::TEAM_LEADER->value,
            RolesEnum::SUPER_ADMIN->value
        ]);

        $this->middleware("role:{$canDeleteUser}")->only('destroy');
    }

    /**
     * Display a listing of the resource.
     *
     * @return UserCollection
     */
    public function index(Request $request)
    {
        $users = $this->userRepository->list($request->toArray());

        return new UserCollection($users);
    }

    /**
     * Get team member list
     *
     * @param Request $request
     * @return UserCollection
     */
    public function teamMember(Request $request): UserCollection
    {
        $teamMembers = $this->userRepository->getTeamMembers($request->toArray());

        return new UserCollection($teamMembers);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreUserRequest $request
     * @return \Illuminate\Http\JsonResponse | UserResource
     */
    public function store(StoreUserRequest $request)
    {
        $result = $this->userRepository->storeUser($request->all());

        if ($result instanceof \Exception) {
            Log::channel('users')
                ->error($result);

            return response()->json(
                [
                    'message' => __('messages.users.create.failed')
                ],
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
        return ( new UserResource($result) )
            ->additional([
                'message' => __('messages.users.create.success')
            ])
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     *
     * @param User $user
     * @return UserResource
     */
    public function show(User $user)
    {
        if ($user->getRoleNames()->first() == RolesEnum::CLIENT->value) {
            $user->load('confidential');
        }

        return new UserResource($user);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateUserRequest $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(UpdateUserRequest $request, User $user)
    {
        $result = $this->userRepository->updateUser($user, $request->all());

        if ($result instanceof \Exception) {
            Log::channel('users')
                ->error($result);

            return response()->json(
                [
                    'message' => __('messages.users.update.failed')
                ],
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }

        return ( new UserResource($result) )
            ->additional([
                'message' => __('messages.users.update.success')
            ])
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param User $user
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(User $user)
    {
        $user->loadCount([
            'assignedToInquiries' => function ($query) {
                $query->currentStatus(InquiryStatusesEnum::ON_PROGRESS->value);
            }
        ]);

        if ($user->assigned_to_inquiries_count > 0) {
            $message = trans('messages.users.delete.failed');
            $statusCode = Response::HTTP_NOT_ACCEPTABLE;
        } else {
            $message = trans('messages.users.delete.success');
            $statusCode = Response::HTTP_OK;

            $user->delete();
        }

        return response()->json(
            [
                'message' => $message
            ],
            $statusCode
        );
    }

    public function responseTime(User $user)
    {
        return $user->average_response_time;
    }

    public function getAverageResponseTimeByRole(Request $request)
    {
        $roles = [RolesEnum::ACCOUNTANT->value, RolesEnum::TEAM_LEADER->value];

        $result = [];

        foreach ($roles as $role) {
            $avgResponseTimes = User::role($role)
                ->get()
                ->transform(function ($item) {
                    $item['average_response_time'] = $item->average_response_time;

                    return $item;
                })
                ->pluck('average_response_time');

            if ($avgResponseTimes->count() <= 0) {
                $result[$role] = [
                    'days' => 0,
                    'hours' => 0,
                    'minutes' => 0,
                ];

                break;
            }

            $sumOfDays = $avgResponseTimes->sum('days');
            $sumOfHours = $avgResponseTimes->sum('hours');
            $sumOfMinutes = $avgResponseTimes->sum('minutes');

            $daysInMinutes = $sumOfDays * 24 * 60;
            $hoursInMinutes = $sumOfHours * 60;

            $totalMinutes = ceil(
                ($daysInMinutes + $hoursInMinutes + $sumOfMinutes) / $avgResponseTimes->count()
            );

            $avgTime = explode(' ', gmdate('d H i', ($totalMinutes)));

            $result[$role] = [
                'days' => $avgTime[0] - 1,
                'hours' => (int) $avgTime[1],
                'minutes' => (int) $avgTime[2]
            ];
        }

        return $result;
    }

    public function pendingTasks(Request $request)
    {
        $userRole = Auth::user()->getRoleNames()->first();

        if (in_array($userRole, [RolesEnum::SUPER_ADMIN->value, RolesEnum::MANAGER->value])) {
            $teamMembers = $this->userRepository->getTeamMembers($request->all());

            return new UserCollection($teamMembers);
        }

        if ($userRole === RolesEnum::CLIENT->value) {
            $askedQuestion = DB::table('question_response_time')
                ->where('questioner', Auth::id())
                ->count();

            $quoted = Inquiry::currentStatus(InquiryStatusesEnum::QUOTED->value)
                ->where('client_id', Auth::id())
                ->count();

            $pendingPayment = Payment::whereRelation('inquiry', 'client_id', Auth::id())
                ->selectRaw('
                    (SUM(debit) - SUM(credit)) AS pending_payment
                ')
                ->where('is_paid', 0)
                ->ofTab(PaymentTabsEnum::FUTURE_DUES->value)
                ->first();

            return [
                'asked_question' => $askedQuestion,
                'quoted' => $quoted,
                'pending_payment' => $pendingPayment->pending_payment
            ];
        }

        if ($userRole === RolesEnum::PROCUREMENT->value) {
            $assigned = Inquiry::where('assigned_to', Auth::id())->count();

            $answeredQuestions = DB::table('question_response_time')
                ->where([
                    'questioned' => Auth::id(),
                    ['replayed_in', '!=', null]
                ])
                ->count();

            $paid = Inquiry::whereHas('statuses', function ($query) {
                    $query->where('name', InquiryStatusesEnum::PAID->value);
                })
                ->where('assigned_to', Auth::id())
                ->count();

            return  [
              'assigned' => $assigned,
              'answered_questions' => $answeredQuestions,
              'paid' => $paid
            ];
        }

        if ($userRole === RolesEnum::TEAM_LEADER->value) {
            $procurementIds = User::select('id')->role(RolesEnum::PROCUREMENT->value)->pluck('id')->toArray();

            $assigned = Inquiry::whereIn('assigned_to', $procurementIds)
                ->count();

            $answeredQuestions = DB::table('question_response_time')
                ->whereIn('questioner', $procurementIds)
                ->count();

            $paid = Inquiry::whereHas('statuses', function ($query) {
                    $query->where('name', InquiryStatusesEnum::PAID->value);
                })
                ->whereIn('assigned_to', $procurementIds)
                ->count();

            return  [
                RolesEnum::PROCUREMENT->value => [
                    'assigned' => $assigned,
                    'answered_questions' => $answeredQuestions,
                    'paid' => $paid
                ],
                RolesEnum::ACCOUNTANT->value => [
                    InquiryStatusesEnum::QUOTATION_PREPARED->value
                        => Inquiry::currentStatus(InquiryStatusesEnum::QUOTATION_PREPARED->value)->count(),
                    InquiryStatusesEnum::APPROVED->value
                        => Inquiry::currentStatus(InquiryStatusesEnum::APPROVED->value)->count(),
                    InquiryStatusesEnum::DELIVERED->value
                    => Inquiry::currentStatus(InquiryStatusesEnum::DELIVERED->value)->count(),
                ]
            ];
        }
    }

    public function statistics(User $user)
    {
        $businessMade = Inquiry::currentStatus(InquiryStatusesEnum::profitable())
            ->withSum('calculations', 'actual_ordered_price_aed')
            ->where('client_id', $user->id)
            ->get()
            ->sum('calculations_sum_actual_ordered_price_aed');

        return [
            'order_ratio' => $user->order_ratio,
            'pending_quotations' => $user->quoted_inquiries,
            'balance' => $user->balance,
            'business_made' => $businessMade
        ];
    }
}
