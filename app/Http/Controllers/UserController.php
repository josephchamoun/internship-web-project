<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Cache;

class UserController extends Controller
{
    public function createManager(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users',
                'password' => 'required|string|min:6',
                'role'=> 'required|string|in:Manager,Employee'
            ]);

            $user = Cache::rememberForever("user_{$validated['email']}", function () use ($validated) {
                return User::create([
                    'name' => $validated['name'],
                    'email' => $validated['email'],
                    'password' => bcrypt($validated['password']),
                    'role' => $validated['role']
                ]);
            });

            $token = $user->createToken('authToken')->plainTextToken;
            $this->clearUserCache();

            return response()->json(['success' => true, 'message' => 'Manager created successfully!', 'user' => $user, 'token' => $token], 201);
        } catch (ValidationException $e) {
            return response()->json(['success' => false, 'errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Something went wrong', 'error' => $e->getMessage()], 500);
        }
    }

    public function index(Request $request)
    {
        $searchTerm = $request->query('search');
        $page = $request->query('page', 1);
        $cacheKey = $searchTerm ? "users_search_{$searchTerm}_page_{$page}" : "users_page_{$page}";

        $userCount = Cache::remember('user_count', now()->addMinutes(10), function () {
            return User::count();
        });

        $users = Cache::remember($cacheKey, now()->addMinutes(10), function () use ($searchTerm) {
            if ($searchTerm) {
                return User::where('name', 'like', '%' . $searchTerm . '%')->simplePaginate(20);
            }
            return User::simplePaginate(12);
        });

        return response()->json(['users' => $users, 'userCount' => $userCount]);
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);

        if (auth()->user()->role !== 'Manager') {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $user->delete();
        Cache::forget("user_{$id}");
        $this->clearUserCache();

        return response()->json(['success' => true, 'redirect_url' => route('users.index')]);
    }

    public function edit($id)
    {
        $user = Cache::remember("user_{$id}", now()->addMinutes(10), function () use ($id) {
            return User::findOrFail($id);
        });

        return view('users.edit', compact('user'));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $id,
        ]);

        $user = User::findOrFail($id);
        $user->update($validated);

        Cache::forget("user_{$id}");
        Cache::forget("user_{$user->email}");
        $this->clearUserCache();

        return redirect()->route('users.index')->with('success', 'User updated successfully.');
    }

    private function clearUserCache()
    {
        for ($i = 1; $i <= 10; $i++) {
            Cache::forget("users_page_{$i}");
            Cache::forget("users_search_*_page_{$i}");
        }
        Cache::forget('user_count');
    }
}
