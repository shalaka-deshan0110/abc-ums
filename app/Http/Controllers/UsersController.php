<?php

namespace App\Http\Controllers;

use App\Models\User;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\URL;
use Illuminate\Validation\Rule;
use Inertia\Inertia;

class UsersController extends Controller
{
    public function index()
    {
        return Inertia::render('Users/Index', [
            'filters' => Request::all('search', 'role', 'trashed'),
            'user-role' => User::role('super-admin')->get(),
            'users' => User::where('is_admin', 0)
                ->orderByName()
                ->filter(Request::only('search'))
                ->paginate(10)
                ->withQueryString()
                ->through(fn ($user) => [
                    'id' => $user->id,
                    'first_name' => $user->first_name,
                    'last_name' => $user->last_name,
                    'name' => $user->name,
                    'email' => $user->email,
                    'is_admin' => $user->is_admin,
                    'photo' => $user->photo_path ? URL::route('image', ['path' => $user->photo_path, 'w' => 40, 'h' => 40, 'fit' => 'crop']) : null,
                    'deleted_at' => $user->deleted_at,
                    'permissions' => $user->permissions,
                    'role'=>$user->getRoleNames(),
                ]),
        ]);
    }

    public function create()
    {
        return Inertia::render('Users/Create');
    }

    public function store()
    {
        if(Gate::allows('create-user')) {
            Request::validate([
                'first_name' => ['required', 'max:50'],
                'last_name' => ['required', 'max:50'],
                'email' => ['required', 'max:50', 'email', Rule::unique('users')],
                'password' => ['nullable'],
                'is_admin' => ['required', 'boolean'],
                'photo' => ['nullable', 'image'],
            ]);

            $user = User::create([
                'first_name' => request()->first_name,
                'last_name' => request()->last_name,
                'email' => request()->email,
                'password' => Hash::make(request()->password),
                'is_admin' => request()->is_admin,
            ]);

            if (Request::file('photo')) {
                $user->updateProfilePhoto(Request::file('photo'));
            }
        }

        return Redirect::route('users')->with('success', 'User created.');
    }

    public function edit(User $user)
    {
        if(Gate::allows('update-user')) {
            return Inertia::render('Users/Edit', [
                'user' => [
                    'id' => $user->id,
                    'first_name' => $user->first_name,
                    'last_name' => $user->last_name,
                    'email' => $user->email,
                    'is_admin' => $user->is_admin,
                    'photo' => $user->photo_path ? URL::route('image', ['path' => $user->photo_path, 'w' => 60, 'h' => 60, 'fit' => 'crop']) : null,
                    'deleted_at' => $user->deleted_at,
                ],
            ]);
        }
    }

    public function update(User $user)
    {
        if(Gate::allows('update-user')) {
            Request::validate([
                'first_name' => ['required', 'max:50'],
                'last_name' => ['required', 'max:50'],
                'email' => ['required', 'max:50', 'email', Rule::unique('users')->ignore($user->id)],
                'password' => ['nullable'],
                'is_admin' => ['required', 'boolean'],
                'photo' => ['nullable', 'image'],
            ]);

            $user->update(Request::only('first_name', 'last_name', 'email', 'is_admin'));

            if (Request::file('photo')) {
                // $user->update(['photo_path' => Request::file('photo')->store('users')]);
            }

            if (Request::get('password')) {
                $user->update(['password' => Request::get('password')]);
            }
        }
        return Redirect::back()->with('success', 'User updated.');
    }

    public function destroy(User $user)
    {
        if(Gate::allows('delete-user')) {
            $user->delete();
        }
        return Redirect::back()->with('success', 'User deleted.');
    }

    public function restore(User $user)
    {
        $user->restore();

        return Redirect::back()->with('success', 'User restored.');
    }
}
