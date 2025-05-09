<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDatasetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('datasets', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('description')->nullable();
            $table->string('source')->nullable();
            $table->string('publication_date')->nullable();
            $table->string('update_frequency')->nullable();
            // web, local, api
            $table->string('data_source_type')->nullable();
            // ファイルパスやURL
            $table->text('data_source_url')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->string('municipality_code', 10)->nullable(); // 追加: 自治体コード
            $table->foreign('municipality_code')->references('code')->on('municipalities'); // 外部キー制約
            $table->index('municipality_code'); // インデックス
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('datasets');
    }
}
