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
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::create('open_data', function (Blueprint $table) {
            $table->id();
            // municipalitiesテーブルのID(外部キー)
            $table->unsignedBigInteger('municipality_id');
            // -- データセットID
            // AED設置箇所は2、避難場所一覧は1など
            $table->unsignedBigInteger('dataset_id')->nullable();
            // -- レコード識別子 (元のデータにあれば)
            // 元のデータにIDカラムがあれば
            $table->string('record_identifier')->nullable();
            // -- タイトル
            $table->string('title')->nullable();
            // データタイプを示すカラム
            // 地図型(map)/時系列型(time_series)/表形式(table)/カテゴリ型(category)/数値型(number)が想定されている
            $table->string('data_type')->nullable();
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();
            $table->json('data')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('municipality_id')->references('id')->on('municipalities');
            $table->foreign('dataset_id')->references('id')->on('datasets'); // 外部キー制約
            $table->index('latitude'); // 通常のインデックスに変更
            $table->index('longitude'); // 通常のインデックスに変更
            $table->index('data_type'); // データタイプで検索する場合に備えてインデックスを追加
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('open_data');
    }
};
