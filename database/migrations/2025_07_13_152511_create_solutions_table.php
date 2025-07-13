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
        Schema::create('solutions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('issue_id')->constrained()->onDelete('cascade')->comment('関連する課題ID'); // issuesテーブルへの外部キー
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null')->comment('提案ユーザーID');
            $table->text('content')->comment('解決策の詳細内容');
            $table->string('status')->default('pending')->comment('解決策の評価状況 (pending, accepted, rejected)');
            $table->unsignedInteger('votes')->default(0)->comment('賛同票数');
            $table->timestamps();

            // 必要であれば、issue_idとuser_idの組み合わせでユニーク制約を設けて、
            // 一人のユーザーが同じ課題に複数回解決策を投稿するのを防ぐことも可能
            // $table->unique(['issue_id', 'user_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('solutions');
    }
};
