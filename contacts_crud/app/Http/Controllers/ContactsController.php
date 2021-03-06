<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreContacts;
use App\Models\Contact;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Str;

class ContactsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Application|Factory|View
     * @throws AuthorizationException
     */
    public function index()
    {
        if (Gate::allows('viewAllAndDeleted')) {
//            QueryBuilder
//            $query = DB::table('contacts')->where('user_id', Auth::id())->get();
//            $contacts = $query->all();
//            Eloquent
            $contacts = Contact::with('user')->onlyTrashed()->orderBy('name')->get();
            return view('contacts.index', compact('contacts'));
        }

        if (Gate::allows('viewAll')) {
            $contacts = Contact::with('user')->orderBy('name')->get();
            return view('contacts.index', compact('contacts'));
        }

        $this->authorize('viewAny', Contact::class);
//        QueryBuilder
//        $query = DB::table('contacts')->where('user_id', Auth::id())->get();
//        $contacts = $query->all();

        $contacts = Contact::where('user_id', Auth::id())->get();
        return view('contacts.index', compact('contacts'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Application|Factory|View
     * @throws AuthorizationException
     */
    public function create()
    {
        $this->authorize('create', Contact::class);

        return view('contacts.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreContacts $request
     * @return RedirectResponse
     */
    public function store(StoreContacts $request): RedirectResponse
    {
//        Raw
//        $request['slug'] = Str::slug($request->name, '-');
//
//        $name = $request->name;
//        $slug = $request->slug;
//        $birth_date = $request->birth_date;
//        $email = $request->email;
//        $phone = $request->phone;
//        $country = $request->country;
//        $address = $request->address;
//        $job_contact = $request->job_contact;
//        $user_id = $request->user()->id;
//
//        DB::insert("insert into contacts (name, slug, birth_date, email, phone, country, address, job_contact, user_id)
//values ($name, $slug, $birth_date, $email, $phone, $country, $address, $job_contact, $user_id)");
//        -----------------------

//        QueryBuilder
//        $request['slug'] = Str::slug($request->name, '-');
//        DB::table('contacts')->insert([
//            'name' => $request->name,
//            'slug' => $request->slug,
//            'birth_date' => $request->birth_date,
//            'email' => $request->email,
//            'phone' => $request->phone,
//            'country' => $request->country,
//            'address' => $request->address,
//            'job_contact' => $request->job_contact,
//            'user_id' => $request->user()->id
//        ]);
//        ------------------------

//        Eloquent
        $request['slug'] = Str::slug($request->name, '-');
        $imgURL = $request->file('file')->storeAS('contacts_img', $request->file->getClientOriginalName());

        $contact = new Contact();
        $contact->name = $request->name;
        $contact->slug = $request->slug;
        $contact->birth_date = $request->birth_date;
        $contact->email = $request->email;
        $contact->phone = $request->phone;
        $contact->country = $request->country;
        $contact->address = $request->address;
        $contact->job_contact = $request->job_contact;
        $contact->user_id = $request->user()->id;
        $contact->image = $imgURL;
        $contact->save();



//        Eloquent
//        $contact = Contact::create($request->all());
//        $contact['slug'] = Str::slug($request->name, '-');
//        $contact->user_id=Auth::id();
//        $contact->save();
//
        return redirect()->route('contacts.index');
    }

    /**
     * Display the specified resource.
     *
     * @param Contact $contact
     * @return Application|Factory|View
     * @throws AuthorizationException
     */
    public function show(Contact $contact)
    {
        $this->authorize('view', $contact);

        return view('contacts.show', compact('contact'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Contact $contact
     * @return Application|Factory|View
     * @throws AuthorizationException
     */
    public function edit(Contact $contact)
    {
        $this->authorize('update', $contact);

        return view('contacts.edit', compact('contact'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param StoreContacts $request
     * @param Contact $contact
     * @return RedirectResponse
     * @throws AuthorizationException
     */
    public function update(StoreContacts $request, Contact $contact): RedirectResponse
    {
        $this->authorize('update', $contact);

        $contact->update($request->all());
        return redirect()->route('contacts.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Contact $contact
     * @return RedirectResponse
     * @throws AuthorizationException
     */
    public function destroy(Contact $contact): RedirectResponse
    {
        $this->authorize('delete', $contact);

        $contact->delete();
        return redirect()->route('contacts.index');
    }
}
