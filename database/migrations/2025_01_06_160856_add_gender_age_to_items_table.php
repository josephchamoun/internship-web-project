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
            $table->enum('age', ['0-3', '3-6', '6-9', '9-12', '13-17', '18+'])->nullable();
           
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
