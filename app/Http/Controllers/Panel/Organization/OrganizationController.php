<?php

namespace App\Http\Controllers\Panel\Organization;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Organization;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\OrgSettings;
use Intervention\Image\ImageManager;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Drivers\Gd\Driver;
use Illuminate\Support\Str;

class OrganizationController extends Controller
{
    // 1️⃣ Index - list all organizations
    public function index()
    {
        $organizations = Organization::get();
        return view('panel.organization.organizations', compact('organizations'));
    }

    // 2️⃣ Create - show add form
    public function create()
    {
        return view('panel.organization.organization_add', );
    }

    // 3️⃣ Store - save new organization
    public function store(Request $request)
    {
        $data = $request->all();

        // Convert datetime-local to MySQL datetime
        if (!empty($data['start'])) {
            $data['start'] = Carbon::parse($data['start'])->format('Y-m-d H:i:s');
        }
        if (!empty($data['end'])) {
            $data['end'] = Carbon::parse($data['end'])->format('Y-m-d H:i:s');
        }

        $data['auth'] = bin2hex(random_bytes(10));
        $data['creator'] = auth()->guard('admin')->user()?->name ?? 'System';

        if ($request->hasFile('logo')) {
            $manager = new ImageManager(new Driver());

            $image = $manager
                ->read($request->file('logo'))
                ->cover(600, 600)       // 600x600 px kesiladi
                ->toWebp(80);           // WebP formatida

            $fileName = uniqid('org_') . '.webp';
            $path = 'organizations/' . $fileName;

            Storage::disk('public')->put($path, $image);

            $data['logo'] = $path;
        }
        
        Organization::create($data);

        OrgSettings::create([
            'org_id' => Organization::latest()->first()->id,
            'global_sync_id' => 1,
            'sync_id' => 1,
            'wifi_name' => 'WiFi',
            'wifi_ip' => 'Not set',
            'editor' => auth()->guard('admin')->user()->name,

        ]);

        return redirect()->route('organizations.index')->with('success', 'Organization saqlandi');
    }

    // Show - view organization details
    public function show($organization)
    {
        $organization = Organization::find($organization);
        $admins = Admin::get();
        return view('panel.organization.organization_view', compact('organization', 'admins'));
    }

    //  Update - update organization
    public function update(Request $request, Organization $organization)
    {
        $data = $request->all();

        // Convert datetime-local to MySQL datetime
        if (!empty($data['start'])) {
            $data['start'] = Carbon::parse($data['start'])->format('Y-m-d H:i:s');
        }
        if (!empty($data['end'])) {
            $data['end'] = Carbon::parse($data['end'])->format('Y-m-d H:i:s');
        }

        // Handle logo upload and resize
        if ($request->hasFile('logo')) {
            // Delete old logo if exists
            if ($organization->logo) {
                Storage::disk('public')->delete($organization->logo);
            }

            $manager = new ImageManager(new Driver());

            $image = $manager
                ->read($request->file('logo'))
                ->cover(600, 600)       // 600x600 px kesiladi
                ->toWebp(80);           // WebP formatida

            $fileName = uniqid('org_') . '.webp';
            $path = 'organizations/' . $fileName;

            Storage::disk('public')->put($path, $image);

            $data['logo'] = $path;
        }

        $organization->update($data);
        return back()->with('success', 'Organization updated successfully.');
    }

    // 7️⃣ Destroy - delete organization
    public function destroy(Organization $organization)
    {
        if ($organization->logo) {
            Storage::disk('public')->delete($organization->logo);
        }

        $organization->delete();

        return redirect()->route('organizations.index')->with('success', 'Organization deleted successfully.');
    }
}
