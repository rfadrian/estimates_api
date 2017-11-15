<?php

namespace App\Http\Controllers;

use App\Models\Estimate;
use App\Models\State;
use App\Models\User;
use Illuminate\Http\Request;

class EstimateController extends Controller
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
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        /*Las llamadas ajax devuelven un error 422 al fallar la validación*/
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
     * Display the specified resource.
     *
     * @param  \App\Models\Estimate  $estimate
     * @return \Illuminate\Http\Response
     */
    public function show(Estimate $estimate)
    {
        //
    }



    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Estimate  $estimate
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Estimate $estimate)
    {
        $validateData = $request->validate([
            'title' => 'nullable|max:255',
            'description' => 'nullable',
            'category_id' => 'nullable|int'
        ]);
        //Si no tenemos campos a editar, salimos.
        if (!$validateData) {
            return $estimate->id;
        }

        if ($estimate->state_id != State::PENDING) {
             return response()->json(['error' => 'Estimate can\'t be modified. State is not pending'],400);
        }

        /*Como si validateData está vacio ya habriamos salido, No tenemos que comprobar que sea un array antes de recorrerlo.*/
        foreach($validateData as $param => $value) {
            $estimate->$param = $value;
        }

        $estimate->save();

        return $estimate->id;
    }


    public function publish(Request $request, Estimate $estimate) {

    }

    public function discard(Estimate $estimate) {

        if ($estimate->state_id == State::DISCARDED) {
            return response()->json(['error' => 'Estimate is already discarded'],400);
        }
        $estimate->state_id = State::DISCARDED;
        $estimate->save();
        return response()->json(['state_id' => $estimate->state_id]);
    }
}
