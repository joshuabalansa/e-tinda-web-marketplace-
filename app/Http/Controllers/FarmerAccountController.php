<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class FarmerAccountController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        return view('farmer.account.index', compact('user'));
    }

    public function updateBusinessInfo(Request $request)
    {
        $request->validate([
            'business_name' => 'required|string|max:255',
            'business_type' => 'required|string|max:100',
            'business_description' => 'nullable|string|max:1000',
            'business_license' => 'nullable|string|max:100',
            'tax_id' => 'nullable|string|max:50',
        ]);

        $user = Auth::user();
        $user->update([
            'business_name' => $request->business_name,
            'business_type' => $request->business_type,
            'business_description' => $request->business_description,
            'business_license' => $request->business_license,
            'tax_id' => $request->tax_id,
        ]);

        return redirect()->back()->with('success', 'Business information updated successfully!');
    }

    public function updateLocation(Request $request)
    {
        $request->validate([
            'farm_address' => 'required|string|max:500',
            'city' => 'required|string|max:100',
            'state' => 'required|string|max:100',
            'zip_code' => 'required|string|max:20',
            'country' => 'required|string|max:100',
            'delivery_radius' => 'required|numeric|min:1|max:100',
            'coordinates' => 'nullable|string|max:100',
        ]);

        $user = Auth::user();
        $user->update([
            'farm_address' => $request->farm_address,
            'city' => $request->city,
            'state' => $request->state,
            'zip_code' => $request->zip_code,
            'country' => $request->country,
            'delivery_radius' => $request->delivery_radius,
            'coordinates' => $request->coordinates,
        ]);

        return redirect()->back()->with('success', 'Location settings updated successfully!');
    }

    public function updatePaymentMethods(Request $request)
    {
        $request->validate([
            'bank_name' => 'nullable|string|max:255',
            'account_number' => 'nullable|string|max:50',
            'routing_number' => 'nullable|string|max:20',
            'paypal_email' => 'nullable|email|max:255',
            'preferred_payment_method' => 'required|in:bank_transfer,paypal,cash',
        ]);

        $user = Auth::user();
        $user->update([
            'bank_name' => $request->bank_name,
            'account_number' => $request->account_number,
            'routing_number' => $request->routing_number,
            'paypal_email' => $request->paypal_email,
            'preferred_payment_method' => $request->preferred_payment_method,
        ]);

        return redirect()->back()->with('success', 'Payment methods updated successfully!');
    }

    public function updatePrivacySettings(Request $request)
    {
        $request->validate([
            'profile_visibility' => 'required|in:public,private,friends_only',
            'show_contact_info' => 'boolean',
            'show_business_info' => 'boolean',
            'allow_messages' => 'boolean',
            'data_sharing' => 'boolean',
        ]);

        $user = Auth::user();
        $user->update([
            'profile_visibility' => $request->profile_visibility,
            'show_contact_info' => $request->has('show_contact_info'),
            'show_business_info' => $request->has('show_business_info'),
            'allow_messages' => $request->has('allow_messages'),
            'data_sharing' => $request->has('data_sharing'),
        ]);

        return redirect()->back()->with('success', 'Privacy settings updated successfully!');
    }

    public function exportData()
    {
        $user = Auth::user();

        $data = [
            'user_info' => [
                'name' => $user->name,
                'email' => $user->email,
                'phone' => $user->phone,
                'address' => $user->address,
                'created_at' => $user->created_at,
            ],
            'business_info' => [
                'business_name' => $user->business_name,
                'business_type' => $user->business_type,
                'business_description' => $user->business_description,
                'business_license' => $user->business_license,
                'tax_id' => $user->tax_id,
            ],
            'location_info' => [
                'farm_address' => $user->farm_address,
                'city' => $user->city,
                'state' => $user->state,
                'zip_code' => $user->zip_code,
                'country' => $user->country,
                'delivery_radius' => $user->delivery_radius,
                'coordinates' => $user->coordinates,
            ],
            'payment_info' => [
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
            'notification_preferences' => [
                'email_notifications' => $user->email_notifications,
                'sms_notifications' => $user->sms_notifications,
                'order_notifications' => $user->order_notifications,
                'marketing_notifications' => $user->marketing_notifications,
            ],
        ];

        $filename = 'farmer_data_' . $user->id . '_' . date('Y-m-d_H-i-s') . '.json';

        return response()->json($data)
            ->header('Content-Disposition', 'attachment; filename="' . $filename . '"')
            ->header('Content-Type', 'application/json');
    }
}
