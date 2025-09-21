<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Personal Information
            $table->string('phone')->nullable();
            $table->text('address')->nullable();
            $table->string('profile_picture')->nullable();

            // Business Information
            $table->string('business_name')->nullable();
            $table->string('business_type')->nullable();
            $table->text('business_description')->nullable();
            $table->string('business_license')->nullable();
            $table->string('tax_id')->nullable();

            // Location Information
            $table->text('farm_address')->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('zip_code')->nullable();
            $table->string('country')->nullable();
            $table->integer('delivery_radius')->nullable();
            $table->string('coordinates')->nullable();

            // Payment Information
            $table->string('bank_name')->nullable();
            $table->string('account_number')->nullable();
            $table->string('routing_number')->nullable();
            $table->string('paypal_email')->nullable();
            $table->string('preferred_payment_method')->nullable();

            // Privacy Settings
            $table->string('profile_visibility')->default('public');
            $table->boolean('show_contact_info')->default(true);
            $table->boolean('show_business_info')->default(true);
            $table->boolean('allow_messages')->default(true);
            $table->boolean('data_sharing')->default(false);

            // Notification Preferences
            $table->boolean('email_notifications')->default(true);
            $table->boolean('sms_notifications')->default(false);
            $table->boolean('order_notifications')->default(true);
            $table->boolean('marketing_notifications')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'phone', 'address', 'profile_picture',
                'business_name', 'business_type', 'business_description', 'business_license', 'tax_id',
                'farm_address', 'city', 'state', 'zip_code', 'country', 'delivery_radius', 'coordinates',
                'bank_name', 'account_number', 'routing_number', 'paypal_email', 'preferred_payment_method',
                'profile_visibility', 'show_contact_info', 'show_business_info', 'allow_messages', 'data_sharing',
                'email_notifications', 'sms_notifications', 'order_notifications', 'marketing_notifications'
            ]);
        });
    }
};
