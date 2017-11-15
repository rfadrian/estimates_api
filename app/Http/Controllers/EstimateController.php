<?php

namespace App\Http\Controllers;

use App\Models\Estimate;
use App\Models\State;
use App\Models\User;
use Illuminate\Http\Request;

class EstimateController extends Controller
{
    /**
     * List estimates.
     * @apiDesc List estimates. Filtered is email is present.
     * @apiParam string $email Email to filter the estimates.
     * @apiParam integer $page Page number.
     * @apiErr 422 | Validation errors
     * @apiResp 200 | list of estimates
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $validateData = $request->validate([
            'email' => 'nullable|email',
            'page' => 'nullable|int'
        ]);

        $estimatesQuery = Estimate::query();

        /*Filtering by email*/
        if ($validateData['email']) {
            $estimatesQuery->whereHas('user', function($query) use ($validateData) {
                $query->where('email', $validateData['email']);
            });
        }
        $estimates = $estimatesQuery->paginate(10);

        return response()->json($estimates);
    }

    /**
     * Create new Estimate.
     * @apiDesc Create new Estimate.
     * @apiParam string $title Estimate Title.
     * @apiParam string $description required | Estimate description.
     * @apiParam integer $category_id Category of the estimate.
     * @apiParam string $user[email] required | User Email.
     * @apiParam string $user[phone] required | User phone.
     * @apiParam string $user[address] required | User Address.
     * @apiErr 422 | Validation errors.
     * @apiErr 418 | Other errors.
     * @apiResp 200 | Created Estimate id
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validateData = $request->validate([
            'title' => 'nullable|max:255',
            'description' => 'required',
            'category_id' => 'nullable|int',
            'user.email' => 'required|email',
            'user.phone' => 'required',
            'user.address' => 'required'
        ]);

        try {
            $userId = $this->saveAndUpdateUser($validateData['user']);
            $estimate = $this->createEstimate($validateData, $userId);
            return $estimate;
        } catch (\Exception $e) {
            \Log::error($e->getMessage());
            return response()->json(['error' => $e->getMessage()],418);
        }
    }

    /**
     * Create a new user if not exists and update phone and address
     * @param array $userData
     * @return integer created/updated user id;
     */
    public function saveAndUpdateUser($userData)
    {
        $user = User::where('email', $userData['email'])
            ->first();
        if (!$user) {
            $user = new User();
            $user->email = $userData['email'];
        }
        $user->phone = $userData['phone'];
        $user->address = $userData['address'];
        $user->save();
        return $user->id;
    }

    /**
     * @param $estimateData
     * @param $user
     * @return integer created estimate id
     */
    private function createEstimate($estimateData, $user)
    {
        $estimate = new Estimate();

        $category = null;
        if (array_has($estimateData, 'category_id')) {
            $category = $estimateData['category_id'];
        }
        $title = null;
        if (array_has($estimateData, 'title')) {
            $title = $estimateData['title'];
        }

        $estimate->description = $estimateData['description'];
        $estimate->title = $title;
        $estimate->category_id = $category;
        $estimate->user_id = $user;
        $estimate->state_id = State::PENDING;
        $estimate->save();

        return $estimate->id;

    }

    /**
     * Update Pending Estimate.
     * @apiDesc Update Pending Estimat.
     * @apiParam string $title Estimate Title.
     * @apiParam string $description Estimate description.
     * @apiParam integer $category_id Category of the estimate.
     * @apiParam integer $estimate Estimate id.
     * @apiErr 422 | Validation errors.
     * @apiErr 400 | Estimate is not pending. Can't be modified.
     * @apiResp 200 | Updated estimate id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Estimate $estimate)
    {
        $validateData = $request->validate([
            'title' => 'nullable|max:255',
            'description' => 'nullable',
            'category_id' => 'nullable|int'
        ]);
        //If there isn't fields to modify, return.
        if (!$validateData) {
            return $estimate->id;
        }

        if ($estimate->state_id != State::PENDING) {
             return response()->json(['error' => 'Estimate can\'t be modified. State is not pending'],400);
        }


        foreach($validateData as $param => $value) {
            $estimate->$param = $value;
        }

        $estimate->save();

        return $estimate->id;
    }

    /**
     * Publish Estimate.
     * @apiDesc Publish Estimate
     * @apiParam integer $estimate Estimate id.
     * @apiErr 400 | Estimate can't be published.
     * @apiResp 200 | Published estimate state_id.
     * @return \Illuminate\Http\Response
     */
    public function publish(Estimate $estimate)
    {
        if ($estimate->isPublishable($estimate)) {
            $estimate->state_id = State::PUBLISHED;
            $estimate->save();
            return response()->json(['state_id' => $estimate->state_id]);
        }

        return response()->json(['error' => 'Estimate doesn\'t meet requirements to be published'],400);
    }

    /**
     * Discard Estimate.
     * @apiDesc Discard Estimate
     * @apiParam integer $estimate Estimate id.
     * @apiErr 400 | Estimate is already discarded.
     * @apiResp 200 | Published estimate state_id.
     * @return \Illuminate\Http\Response
     */
    public function discard(Estimate $estimate)
    {

        if ($estimate->state_id == State::DISCARDED) {
            return response()->json(['error' => 'Estimate is already discarded'],400);
        }
        $estimate->state_id = State::DISCARDED;
        $estimate->save();
        return response()->json(['state_id' => $estimate->state_id]);
    }
}
