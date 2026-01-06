<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class FarmerAccountController extends Controller
{
    /**
     * Display farmer account settings.
     */
    public function index()
    {
        $user = Auth::user();

        return view('farmer.account.index', compact('user'));
    }

    /**
     * Update farmer account settings.
     */
    public function update(Request $request)
    {
        $user = Auth::user();

        // Build validation rules based on what fields are present
        $rules = [];

        // Only validate fields that are actually in the request
        if ($request->has('name')) {
            $rules['name'] = 'required|string|max:255';
        }
        if ($request->has('email')) {
            $rules['email'] = 'required|email|unique:users,email,' . $user->id;
        }
        if ($request->has('phone')) {
            $rules['phone'] = 'nullable|string|max:20';
        }
        if ($request->has('address')) {
            $rules['address'] = 'nullable|string|max:500';
        }
        if ($request->has('business_name')) {
            $rules['business_name'] = 'nullable|string|max:255';
        }
        if ($request->has('business_type')) {
            $rules['business_type'] = 'nullable|string|max:100';
        }
        if ($request->has('business_description')) {
            $rules['business_description'] = 'nullable|string|max:1000';
        }
        if ($request->has('business_license')) {
            $rules['business_license'] = 'nullable|string|max:255';
        }
        if ($request->has('tax_id')) {
            $rules['tax_id'] = 'nullable|string|max:255';
        }
        if ($request->has('farm_address')) {
            $rules['farm_address'] = 'nullable|string|max:500';
        }
        if ($request->has('city')) {
            $rules['city'] = 'nullable|string|max:100';
        }
        if ($request->has('state')) {
            $rules['state'] = 'nullable|string|max:100';
        }
        if ($request->has('zip_code')) {
            $rules['zip_code'] = 'nullable|string|max:20';
        }
        if ($request->has('country')) {
            $rules['country'] = 'nullable|string|max:100';
        }
        if ($request->has('delivery_radius')) {
            $rules['delivery_radius'] = 'nullable|integer|min:0|max:100';
        }
        if ($request->has('coordinates')) {
            $rules['coordinates'] = 'nullable|string|max:255';
        }
        if ($request->has('password')) {
            $rules['password'] = 'nullable|string|min:8|confirmed';
        }

        $request->validate($rules);

        // Update only the fields that are present in the request
        if ($request->has('name')) {
            $user->name = $request->name;
        }
        if ($request->has('email')) {
            $user->email = $request->email;
        }
        if ($request->has('phone')) {
            $user->phone = $request->phone;
        }
        if ($request->has('address')) {
            $user->address = $request->address;
        }
        if ($request->has('business_name')) {
            $user->business_name = $request->business_name;
        }
        if ($request->has('business_type')) {
            $user->business_type = $request->business_type;
        }
        if ($request->has('business_description')) {
            $user->business_description = $request->business_description;
        }
        if ($request->has('business_license')) {
            $user->business_license = $request->business_license;
        }
        if ($request->has('tax_id')) {
            $user->tax_id = $request->tax_id;
        }
        if ($request->has('farm_address')) {
            $user->farm_address = $request->farm_address;
        }
        if ($request->has('city')) {
            $user->city = $request->city;
        }
        if ($request->has('state')) {
            $user->state = $request->state;
        }
        if ($request->has('zip_code')) {
            $user->zip_code = $request->zip_code;
        }
        if ($request->has('country')) {
            $user->country = $request->country;
        }
        if ($request->has('delivery_radius')) {
            $user->delivery_radius = $request->delivery_radius;
        }
        if ($request->has('coordinates')) {
            $user->coordinates = $request->coordinates;
        }

        // Update password if provided
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        return redirect()->back()
            ->with('success', 'Account updated successfully!');
    }

    /**
     * Export farmer account data.
     */
    public function exportData()
    {
        $user = Auth::user();

        $data = [
            'personal_information' => [
                'name' => $user->name,
                'email' => $user->email,
                'phone' => $user->phone,
                'address' => $user->address,
            ],
            'business_information' => [
                'business_name' => $user->business_name,
                'business_type' => $user->business_type,
                'business_description' => $user->business_description,
                'business_license' => $user->business_license,
                'tax_id' => $user->tax_id,
            ],
            'location_settings' => [
                'farm_address' => $user->farm_address,
                'city' => $user->city,
                'state' => $user->state,
                'zip_code' => $user->zip_code,
                'country' => $user->country,
                'delivery_radius' => $user->delivery_radius,
                'coordinates' => $user->coordinates,
            ],
            'payment_methods' => [
                'bank_name' => $user->bank_name,
                'account_number' => $user->account_number,
                'routing_number' => $user->routing_number,
                'paypal_email' => $user->paypal_email,
                'preferred_payment_method' => $user->preferred_payment_method,
            ],
            'privacy_settings' => [
                'profile_visibility' => $user->profile_visibility,
                'show_contact_info' => $user->show_contact_info,
                'show_business_info' => $user->show_business_info,
                'allow_messages' => $user->allow_messages,
                'data_sharing' => $user->data_sharing,
            ],
            'notification_settings' => [
                'email_notifications' => $user->email_notifications,
                'sms_notifications' => $user->sms_notifications,
                'order_notifications' => $user->order_notifications,
                'marketing_notifications' => $user->marketing_notifications,
            ],
            'account_status' => [
                'is_active' => $user->is_active,
                'created_at' => $user->created_at,
                'updated_at' => $user->updated_at,
            ],
            'export_timestamp' => now()->toISOString(),
        ];

        $filename = 'farmer_account_data_' . $user->id . '_' . now()->format('Y-m-d_H-i-s') . '.json';

        return response()->json($data, 200, [
            'Content-Type' => 'application/json',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ]);
    }
}