<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('municipalities', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();             // 自治体名
            $table->string('code', 10)->unique()->nullable(); // 自治体ID(JISコード的なもの)
            $table->string('source')->nullable();           // データ提供元 (例: 川口市)
            $table->timestamps();
            $table->softDeletes();

            $table->index('code');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('municipalities');
    }
};
