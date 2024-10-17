<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserController extends Controller
{
    // Obtener todos los usuarios
    public function index()
    {
        $users = User::all();
        return response()->json($users, 200);
    }

    // Crear un nuevo usuario
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'role' => 'in:admin,user,manager,customer,cashier',
        ]);

        $user = User::create([
            'name' => $validatedData['name'],
            'email' => $validatedData['email'],
            'password' => Hash::make($validatedData['password']),
            'role' => $request->role ?? 'user',
        ]);

        return response()->json($user, 201);
    }

    // Obtener un usuario por ID
    public function show($id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json(['error' => 'Usuario no encontrado'], 404);
        }

        return response()->json($user, 200);
    }

    // Actualizar un usuario
    public function update(Request $request, $id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json(['error' => 'Usuario no encontrado'], 404);
        }

        $validatedData = $request->validate([
            'name' => 'sometimes|string|max:255',
            'email' => 'sometimes|string|email|max:255|unique:users,email,' . $id,
            'password' => 'sometimes|string|min:8',
            'role' => 'in:admin,user,manager,customer,cashier',
        ]);

        if ($request->has('name')) {
            $user->name = $validatedData['name'];
        }

        if ($request->has('email')) {
            $user->email = $validatedData['email'];
        }

        if ($request->has('password')) {
            $user->password = Hash::make($validatedData['password']);
        }

        if ($request->has('role')) {
            $user->role = $validatedData['role'];
        }

        $user->save();

        return response()->json($user, 200);
    }

    // Eliminar un usuario
    public function destroy($id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json(['error' => 'Usuario no encontrado'], 404);
        }

        $user->delete();

        return response()->json(['message' => 'Usuario eliminado correctamente'], 200);
    }
}
