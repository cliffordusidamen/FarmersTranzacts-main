<?php

namespace App\Http\Controllers;

use App\Http\Resources\OrderCollection;
use App\Http\Resources\UserCollection;
use App\Models\Department;
use App\Models\DepartmentSection;
use App\Models\Designation;
use App\Models\Role;
use App\Models\State;
use App\Models\User;
use App\Models\VendorOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    /**
     * Create instance of the controller
     */
    public function __construct()
    {
        // To do: add middleware for adminstrators only
        $this->middleware(['auth:sanctum', 'frontend']);
    }

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
     * Display a listing of the resource, filtered by indicated designation.
     *
     * @param  string  $designation
     * @return \Illuminate\Http\Response
     */
    public function list($designation)
    {
        $searchText = trim(request()->query('q', ''));
        $searchDesignation = Str::singular($designation);
        $relations = [
            'roles', 'designation', 'department', 'departmentSection',
            'referrer',
        ];

        if (strtolower($searchDesignation) == Designation::SELLER_NAME) {
            $relations[] = 'vendors';
            $relations[] = 'vendors.productCategories';
        }
        if (strtolower($searchDesignation) == Designation::AGENT_NAME) {
            $relations[] = 'agent';
            $relations[] = 'agent.spendingVolume';
            $relations[] = 'agent.productCategories';
        }

        $users = User::with($relations)
            ->withCount('roles')
            ->designated($searchDesignation)
            ->filterNameAndContact($searchText)
            ->orderByDesc('id')
            ->paginate(15);

        return new UserCollection($users);
    }

    /**
     * Get data needed for rendering staff form
     * 
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function editStaff($id)
    {
        $user= User::with(['address', 'roles'])
            ->designated(Designation::STAFF_NAME)
            ->where('id', (int) $id)
            ->firstOrFail();

        $states = State::all();
        $departments = Department::all();
        $department_sections = DepartmentSection::all();
        $roles = Role::all();

        return response()->json(compact(
            'states', 'departments', 'department_sections', 'roles',
            'user'
        ));
    }

    /**
     * Fetch data needed to render form for staff
     * 
     * @return \Illuminate\Http\Response
     */
    public function addStaff()
    {
        $states = State::all();
        $departments = Department::all();
        $department_sections = DepartmentSection::all();
        $roles = Role::all();

        return response()->json(compact(
            'states', 'departments', 'department_sections', 'roles'
        ));
    }

    /**
     * Store a newly created staff in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function storeStaff(Request $request)
    {
        $data = $this->_getStaffRequestData();

        $rules = $this->_getStaffValidationRules();

        $password = Str::random(8);
        $data = Validator::make($data, $rules)->validate();
        $data['password'] = $password;

        $user = $this->_createOrUpdateStaff($data);

        if (!$user) {
            return response()->json([
                'save_failed' => true,
                'message' => 'Staff information not saved! Please, try again.'
            ]);
        }

        $user->sendStaffWelcomeNotification($password);

        return response()->json(compact('user'));
    }

    /**
     * Store a newly created staff in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function updateStaff(Request $request, $id)
    {
        $user = User::findOrFail((int) $id);
        $data = $this->_getStaffRequestData();

        $rules = $this->_getStaffValidationRules((int) $id);

        $data = Validator::make($data, $rules)->validate();

        $user = $this->_createOrUpdateStaff($data, $user);

        if (!$user) {
            return response()->json([
                'save_failed' => true,
                'message' => 'Staff information not saved! Please, try again.'
            ]);
        }

        return response()->json(compact('user'));
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
     * Update personal information
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function updateProfile(Request $request)
    {
        $user = User::find($request->user()->id);
        $data = $this->validate($request, User::defaultValidationRules($user->id));

        $saved = $user->forceFill($data)->save();

        if (!$saved) {
            throw new \Exception('Profile information not saved! Please, try again.');
        }

        return response()->json(compact('user'));
    }

    /**
     * Change personal password
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function changePassword(Request $request)
    {
        $this->validate($request, [
            'password' => 'required|min:6|max:50|confirmed',
            'password_confirmation' => 'required|min:6|max:50',
        ]);
        $user = User::find($request->user()->id);
        $user->password = $request->input('password');
        if ($user->save()) {
            return response()->json(compact('user'));
        }

        throw new \Exception('Password was not changed! Please, try again.');
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
     * Read expected keys from the request for creating/updating staff details
     * 
     * @return array
     */
    public function _getStaffRequestData(): array
    {
        return request()->only([
            'first_name', 'last_name', 'phone', 'email', 'gender',
            'is_admin',
            'department_id', 'department_section_id',
            'line_1', 'line_2', 'city', 'state_id',
            'role_id',
        ]);
    }

    /**
     * Get validation rules for staff creation/edit request
     * 
     * @param  int|null  $userId
     * @return array
     */
    public function _getStaffValidationRules(?int $userId = null): array
    {
        $phoneUniqueRule = Rule::unique('users', 'phone');
        $emailUniqueRule = Rule::unique('users', 'email');

        if (!is_null($userId)) {
            $phoneUniqueRule->ignore((int) $userId);
            $emailUniqueRule->ignore((int) $userId);
        }

        $rules = [
            'first_name' => ['required', 'string', 'min:3', 'max:20'],
            'last_name' => ['required', 'string', 'min:3', 'max:20'],
            'phone' => ['required', 'string', 'min:11', 'max:15', $phoneUniqueRule],
            'email' => ['required', 'string', 'email', $emailUniqueRule],
            'gender' => ['sometimes', 'nullable', Rule::in(User::GENDER_FEMALE, User::GENDER_MALE)],
            'is_admin' => ['sometimes', 'nullable', 'boolean'],
            'department_id' => ['sometimes', 'nullable', 'exists:departments,id'],
            'department_section_id' => ['sometimes', 'nullable', 'exists:department_sections,id'],
            'line_1' => ['required', 'string', 'min:5', 'max:100'],
            'line_2' => ['sometimes', 'nullable', 'string', 'min:5', 'max:100'],
            'city' => ['sometimes', 'nullable', 'string', 'min:3', 'max:100'],
            'state_id' => ['sometimes', 'nullable', 'exists:states,id'],
            'role_id' => ['sometimes', 'nullable', 'exists:roles,id'],
        ];
        
        return $rules;
    }

    /**
     * Saves (create/update) staff record in the database
     * 
     * @param  array  $data
     * @param  User|null  $user
     * @return User|bool
     */
    private function _createOrUpdateStaff(array $data, ?User $user = null)
    {
        $isCreate = is_null($user);
        $user = $user ?? new User();
        $savedUser = false;

        DB::transaction(function() use ($data, $isCreate, &$savedUser, &$user) {
            $dataToSave = [
                'designation_id' => Designation::STAFF_ID,
                'first_name' => $data['first_name'],
                'last_name' => $data['last_name'],
                'phone' => $data['phone'],
                'email' => $data['email'],
                'gender' => $data['gender'] ?? null,
                'department_id' => $data['department_id'] ?? null,
                'department_section_id' => $data['department_section_id'] ?? null,
                'is_admin' => $data['is_admin'] ?? false,
            ];

            if (!empty($data['password'])) {
                $dataToSave['password'] = $data['password'];
                $dataToSave['require_password_change'] = true;
            }

            $user->forceFill($dataToSave);
            $user->save();

            $addressData = [
                'line_1' => $data['line_1'],
                'line_2' => $data['line_2'] ?? null,
                'city' => $data['city'] ?? null,
                'state_id' => $data['state_id'],
            ];

            $addressSaved = $isCreate
                ? $user->address()->make($addressData)->save()
                : $user->address->forceFill($addressData)->save();

            if (
                !$isCreate
                && $user->roles()->count()
                && $user->roles->first()->id != $data['role_id']
            ){
                $user->removeRole($user->roles->first()->name);
            }

            if (!$data['is_admin']) {
                $role = Role::findOrFail($data['role_id']);
                $user->assignRole($role->name);
            }

            $savedUser = true;
        });

        return $savedUser ? $user : false;
        
    }

    /**
     * Agent or shopper information
     * 
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function showAgentOrShopper(Request $request, $id)
    {
        $rq_user = User::with(['designation'])->findOrFail($id);
        if (
            $rq_user->designation_id != Designation::AGENT_ID
            && $rq_user->designation_id != Designation::SHOPPER_ID
        ) {
            abort(404);
        }

        if ($rq_user->designation_id = Designation::AGENT_ID) {
            $rq_user->load([
                'agent',
                'agent.spendingVolume',
                'agent.productCategories',
                'agent.address',
                'agent.address.state',
                'agent.address.localGovernmentArea',
            ]);
        }

        $rq_user->append('full_name')->append('profile_photo_url');
        $spendingTrendData = VendorOrder::getUserMonthlyPurchases(
            8, request()->user()->id);

        return response()->json(compact(
            'rq_user', 'spendingTrendData'
        ));
    }

    /**
     * Staff information
     * 
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function showStaff(Request $request, int $id)
    {
        $staff = User::designated(Designation::STAFF_NAME)
            ->with(['designation', 'department', 'departmentSection', 'roles'])
            ->findOrFail($id);
        $staff->append('full_name')->append('profile_photo_url');

        return response()->json(compact(
            'staff'
        ));
    }

    /**
     * Seller account information
     * 
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function showSeller(Request $request, int $id)
    {
        $rq_seller = User::designated(Designation::SELLER_NAME)
            ->with(['vendors'])
            ->findOrFail($id);
        $rq_seller->append('full_name')
            ->append('profile_photo_url')
            ->append('vendor_data');

        return response()->json(compact(
            'rq_seller'
        ));
    }

    /**
     * Get records of users orders
     * 
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function userOrders(Request $request, int $id)
    {
        $user = User::findOrFail($id);
        $ordersQuery = $user->orders()
            ->with(['deliveryStatus', 'vendorOrders'])
            ->withCount(['cancelledVendorOrder']);

        $q = trim($request->query('q', ''));
        if (strlen($q) > 0) {
            $ordersQuery->where('reference_number', 'like', "%{$q}%");
        }
        $orders = $ordersQuery->paginate(10);

        if ($orders->total()) {
            $orders->each(fn ($order) => $order->append('total_quantity'));
        }

        return new OrderCollection($orders);
    }
}
