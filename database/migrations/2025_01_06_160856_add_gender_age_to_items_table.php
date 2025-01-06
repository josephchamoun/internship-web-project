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
        Schema::table('items', function (Blueprint $table) {
            $table->enum('gender', ['female', 'male', 'both'])->default('both');
            $table->integer('age')->nullable();
           
        });
    }

    public function down()
    {
        Schema::table('items', function (Blueprint $table) {
            $table->dropColumn('gender');
            $table->dropColumn('age');
            
        });
    }
};
