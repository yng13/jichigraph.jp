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
        Schema::create('points_of_interest', function (Blueprint $table) {
            $table->id();
            // どの自治体のデータかを識別
            $table->string('municipality_code')->nullable()->comment('全国地方公共団体コード');
            $table->string('municipality_name')->nullable()->comment('地方公共団体名');

            // データの種類を識別 (例: 'AED', 'EvacuationShelter' など)
            $table->string('type')->comment('地点の種類');

            // 汎用的な地点情報
            $table->string('name')->comment('名称');
            $table->string('name_kana')->nullable()->comment('名称_カナ');
            $table->string('name_english')->nullable()->comment('名称_英字');
            $table->text('address')->nullable()->comment('所在地_連結表記'); // 住所が長くなる可能性を考慮
            $table->string('prefecture')->nullable()->comment('所在地_都道府県');
            $table->string('city')->nullable()->comment('所在地_市区町村');
            $table->string('town')->nullable()->comment('所在地_町字');
            $table->string('block_number')->nullable()->comment('所在地_番地以下');
            $table->string('building_name')->nullable()->comment('建物名等(方書)');
            $table->double('latitude', 15, 12)->comment('緯度'); // 精度を上げる
            $table->double('longitude', 15, 12)->comment('経度'); // 精度を上げる
            $table->string('phone_number')->nullable()->comment('電話番号');
            $table->string('contact_email')->nullable()->comment('連絡先メールアドレス');
            $table->string('url')->nullable()->comment('関連URL');
            $table->text('remarks')->nullable()->comment('備考');

            // 各地点タイプ固有の詳細情報をJSON形式で格納
            $table->json('details')->nullable()->comment('地点固有の詳細情報 (JSON形式)');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('points_of_interest');
    }
};
