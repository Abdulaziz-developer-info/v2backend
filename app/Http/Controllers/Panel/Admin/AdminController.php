<?php

namespace App\Http\Controllers\Panel\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Admin;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class AdminController extends Controller
{
    public function login_page()
    {
        return view('panel.login');
    }
    public function login(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string',
            'password' => 'required|string',
        ]);

        if (
            Auth::guard('admin')->attempt([
                'name' => $data['name'],
                'password' => $data['password'],
            ], true)
        ) {

            $request->session()->regenerate();
            return redirect()->route('admin.dashboard');
        }
        return back()->withErrors([
            'name' => "Login yoki parol noto'g'ri",
        ]);
    }
    public function logout(Request $request)
    {
        Auth::guard('admin')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('admin.login');
    }

    public function index()
    {
        $admins = Admin::latest()->get();
        return view('panel.admin.index', compact('admins'));
    }
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255|unique:admins,name',
            'password' => 'required|string|min:6',
            'role' => 'required|in:1,2,3',
            'message' => 'nullable|string',
            'avatar' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'phone' => 'nullable|string|max:30',
            'telegram' => 'nullable|string|max:100',
            'instagram' => 'nullable|string|max:100',
            'whatsapp' => 'nullable|string|max:30',
            'work_start' => 'nullable',
            'work_end' => 'nullable',
            'work_days' => 'nullable|string|max:255',
        ]);

        // Parolni saqlashdan oldin hash qilish
        $plainPassword = $request->password;
        $data['password'] = bcrypt($plainPassword);

        // Rasmni qayta ishlash
        if ($request->hasFile('avatar')) {
            $manager = new ImageManager(new Driver());
            $image = $manager->read($request->file('avatar'))->cover(300, 300)->toWebp(80);
            $fileName = uniqid('admin_') . '.webp';
            $path = 'admins/' . $fileName;
            Storage::disk('public')->put($path, $image);
            $data['avatar'] = $path;
        }

        Admin::create($data);

        return redirect()->back()->with('admin_credentials', [
            'login' => $data['name'],
            'password' => $plainPassword
        ]);
    }

    public function update(Request $request, Admin $admin)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255|unique:admins,name,' . $admin->id,
            'role' => 'required|in:1,2,3',
            'block' => 'required|in:0,1,2',
            'password' => 'nullable|string|min:6',
            'message' => 'nullable|string',
            'avatar' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'phone' => 'nullable|string|max:30',
            'telegram' => 'nullable|string|max:100',
            'instagram' => 'nullable|string|max:100',
            'whatsapp' => 'nullable|string|max:30',
            'work_start' => 'nullable',
            'work_end' => 'nullable',
            'work_days' => 'nullable|string|max:255',
        ]);

        if ($request->filled('password')) {
            $data['password'] = bcrypt($request->password);
            $plainPassword = $request->password;
        } else {
            unset($data['password']);
            $plainPassword = "O'zgartirilmadi";
        }

        if ($request->hasFile('avatar')) {
            if ($admin->avatar) {
                Storage::disk('public')->delete($admin->avatar);
            }
            $manager = new ImageManager(new Driver());
            $image = $manager->read($request->file('avatar'))->cover(300, 300)->toWebp(80);
            $fileName = uniqid('admin_') . '.webp';
            $path = 'admins/' . $fileName;
            Storage::disk('public')->put($path, $image);
            $data['avatar'] = $path;
        }

        $admin->update($data);

        return redirect()->back()->with('admin_credentials', [
            'login' => $admin->name,
            'password' => $plainPassword
        ]);
    }
    public function destroy(Admin $admin)
    {
        if ($admin->avatar) {
            Storage::disk('public')->delete($admin->avatar);
        }

        $admin->delete();

        return redirect()->back()->with('success', 'Admin deleted');
    }
}
