<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('issues', function (Blueprint $table) {
            $table->id();
            $table->string('title')->comment('課題のタイトル');
            $table->text('description')->comment('課題の詳細な説明');
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null')->comment('投稿ユーザーID');
            $table->string('status')->default('open')->comment('課題のステータス (open, in_progress, resolved)');
            $table->string('municipality_code')->nullable()->comment('関連する全国地方公共団体コード');
            $table->double('latitude', 15, 12)->comment('課題発生場所の緯度');
            $table->double('longitude', 15, 12)->comment('課題発生場所の経度');
            $table->string('image_url')->nullable()->comment('課題に関連する画像のURL');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('issues');
    }
};
