<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {

        Schema::create('workspaces', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->boolean('is_active');
            $table->string('support_email');
            $table->string('settings_flags');
            $table->string('feature_flags');
            $table->float('auto_resolve_duration');
            $table->float('auto_response_time');
            $table->string('confirmation_message');
            $table->string('absence_message');
            $table->string('address');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('workspaces');
    }
};
