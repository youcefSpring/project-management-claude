<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Platform owner: manages plans and the public landing page across all organizations
        Schema::table('users', function (Blueprint $table) {
            $table->boolean('is_super_admin')->default(false)->after('role');
        });

        Schema::create('plans', function (Blueprint $table) {
            $table->id();
            $table->string('slug', 60)->unique();
            $table->decimal('price', 10, 2)->nullable();   // null = quoted price ("let us talk")
            $table->string('currency', 10)->default('DA');
            $table->boolean('is_free')->default(false);
            $table->string('cta_type', 20)->default('register'); // register | contact
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_active')->default(true);
            $table->unsignedInteger('sort_order')->default(0);
            $table->json('translations');                  // { en: {name, audience, price_label, features: []}, fr: {...}, ar: {...} }
            $table->timestamps();

            $table->index(['is_active', 'sort_order'], 'idx_plans_active_order');
        });

        Schema::create('landing_contents', function (Blueprint $table) {
            $table->id();
            $table->string('key', 120);      // matches a key in resources/lang/*/landing.php, e.g. hero.title_line1
            $table->string('locale', 5);
            $table->text('value');
            $table->timestamps();

            $table->unique(['key', 'locale'], 'uniq_landing_contents_key_locale');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('landing_contents');
        Schema::dropIfExists('plans');

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('is_super_admin');
        });
    }
};
