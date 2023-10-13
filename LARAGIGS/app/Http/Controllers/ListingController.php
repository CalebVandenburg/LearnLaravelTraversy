<?php

namespace App\Http\Controllers;

use App\Models\Listing;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ListingController extends Controller
{
    //show all listings
    public function index() {

        return view('listings.index', [
            'heading' => 'Latest Listings',
            //latest sorts by latest.
            'listings' => Listing::latest()->filter(request(['tag', 'search']))->paginate(2)
        ]);

    }
    //show single listing
    public function show(Listing $listing) {

        return view('listings.show', [ 
            'listing' => $listing
        ]);
    }
    //show single listing
    public function edit(Listing $listing) {
        return view('listings.edit', [ 
            'listing' => $listing
        ]);
    }//show single listing
    //show single listing
    public function update(Request $request, Listing $listing) {
        $formFields = $request->validate([
            'title' => 'required',
            'company' => ['required'],
            'location' => 'required',
            'website' => 'required',
            'email' => ['required','email'],
            'tags' => 'required',
            'description' => 'required'
        ]);
        if($request->hasFile('logo')) {
            $formFields['logo'] = $request->file('logo')->store('logos','public');
        }
        $listing->update($formFields);

        return back()->with('message', 'Updated');
    }
    //show single listing
    public function store(Request $request) {
        $formFields = $request->validate([
            'title' => 'required',
            'company' => ['required', Rule::unique('listings', 'company')],
            'location' => 'required',
            'website' => 'required',
            'email' => ['required','email'],
            'tags' => 'required',
            'description' => 'required'
        ]);
        if($request->hasFile('logo')) {
            $formFields['logo'] = $request->file('logo')->store('logos','public');
        }
        Listing::create($formFields);

        return redirect('/')->with('message', 'Created');
    }
    //show single listing
    public function create() {

        return view('listings.create');
    }
    public function destroy(Listing $listing) {
        $listing->delete();
        return redirect('/')->with('message', 'Deleted');
    }
}
