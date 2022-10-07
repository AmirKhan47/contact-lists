<?php

namespace App\Http\Controllers;

use App\Jobs\ProcessImport;
use App\Models\UserContact;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Bus;

class UserContactController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $contacts = UserContact::where('user_id',  auth()->user()->id)->with('userCategory:id,name')->get();
        return view('contacts.index', compact('contacts'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categories = auth()->user()->categories;
        return view('contacts.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'phone' => 'required',
        ]);

        $contact = new UserContact();
        $contact->name = $request->name;
        $contact->phone = $request->phone;
        $contact->user_category_id = $request->category;
        $contact->user_id = auth()->user()->id;
        $contact->save();

        return redirect()->route('contacts.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\UserContact  $userContact
     * @return \Illuminate\Http\Response
     */
    public function show(UserContact $userContact)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\UserContact  $userContact
     * @return \Illuminate\Http\Response
     */
    public function edit(UserContact $userContact, $id)
    {
        $contact = UserContact::where('id', $id)->with('userCategory:id,name')->first();
        $categories = auth()->user()->categories;
        return view('contacts.edit', compact('contact', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\UserContact  $userContact
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, UserContact $userContact, $id)
    {
        $request->validate([
            'name' => 'required',
            'phone' => 'required',
        ]);

        $contact = UserContact::find($id);
        $contact->name = $request->name;
        $contact->phone = $request->phone;
        $contact->user_category_id = $request->category;
        $contact->user_id = auth()->user()->id;
        $contact->save();

        return redirect()->route('contacts.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\UserContact  $userContact
     * @return \Illuminate\Http\Response
     */
    public function destroy(UserContact $userContact, $id)
    {
        $contact = UserContact::find($id);
        $contact->delete();

        return redirect()->route('contacts.index');
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:csv'
        ]);

        if (request()->has('file')) {
            $data = file(request()->file);
            // Chunking file
            $chunks = array_chunk($data, 1000);

            $header = [];
            $batch  = Bus::batch([])->dispatch();

            foreach ($chunks as $key => $chunk) {
                $data = array_map('str_getcsv', $chunk);

                if ($key === 0) {
                    $header = $data[0];
                    unset($data[0]);
                }

                $batch->add(new ProcessImport($data, $header));
            }

            return $batch;
        }

        return 'please upload file';

//        return redirect()->route('contacts.import');
    }
}
