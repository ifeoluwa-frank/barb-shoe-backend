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
            //
            $table->string('firstname')->after('id');
            $table->string('lastname')->after('firstname');
            $table->string('role')->default('user')->after('password');

            // Drop the old name column
            $table->dropColumn('name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            //
            $table->string('name')->after('id');

            // Drop the new columns
            $table->dropColumn('firstname');
            $table->dropColumn('lastname');
            $table->dropColumn('role');
        });
    }
};
