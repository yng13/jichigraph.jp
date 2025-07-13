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
        Schema::create('population_statistics', function (Blueprint $table) {
            $table->id();
            $table->string('municipality_code')->comment('全国地方公共団体コード');
            $table->string('municipality_name')->comment('地方公共団体名');
            $table->string('survey_date')->comment('調査年月日'); // CSVの形式に合わせて文字列で保持
            $table->string('region_code')->nullable()->comment('地域コード');
            $table->string('region_name')->nullable()->comment('地域名');
            $table->unsignedInteger('total_population')->comment('総人口');
            $table->unsignedInteger('male_population')->comment('男性人口');
            $table->unsignedInteger('female_population')->comment('女性人口');
            $table->unsignedInteger('male_0_4')->comment('0-4歳の男性');
            $table->unsignedInteger('female_0_4')->comment('0-4歳の女性');
            $table->unsignedInteger('male_5_9')->comment('5-9歳の男性');
            $table->unsignedInteger('female_5_9')->comment('5-9歳の女性');
            $table->unsignedInteger('male_10_14')->comment('10-14歳の男性');
            $table->unsignedInteger('female_10_14')->comment('10-14歳の女性');
            $table->unsignedInteger('male_15_19')->comment('15-19歳の男性');
            $table->unsignedInteger('female_15_19')->comment('15-19歳の女性');
            $table->unsignedInteger('male_20_24')->comment('20-24歳の男性');
            $table->unsignedInteger('female_20_24')->comment('20-24歳の女性');
            $table->unsignedInteger('male_25_29')->comment('25-29歳の男性');
            $table->unsignedInteger('female_25_29')->comment('25-29歳の女性');
            $table->unsignedInteger('male_30_34')->comment('30-34歳の男性');
            $table->unsignedInteger('female_30_34')->comment('30-34歳の女性');
            $table->unsignedInteger('male_35_39')->comment('35-39歳の男性');
            $table->unsignedInteger('female_35_39')->comment('35-39歳の女性');
            $table->unsignedInteger('male_40_44')->comment('40-44歳の男性');
            $table->unsignedInteger('female_40_44')->comment('40-44歳の女性');
            $table->unsignedInteger('male_45_49')->comment('45-49歳の男性');
            $table->unsignedInteger('female_45_49')->comment('45-49歳の女性');
            $table->unsignedInteger('male_50_54')->comment('50-54歳の男性');
            $table->unsignedInteger('female_50_54')->comment('50-54歳の女性');
            $table->unsignedInteger('male_55_59')->comment('55-59歳の男性');
            $table->unsignedInteger('female_55_59')->comment('55-59歳の女性');
            $table->unsignedInteger('male_60_64')->comment('60-64歳の男性');
            $table->unsignedInteger('female_60_64')->comment('60-64歳の女性');
            $table->unsignedInteger('male_65_69')->comment('65-69歳の男性');
            $table->unsignedInteger('female_65_69')->comment('65-69歳の女性');
            $table->unsignedInteger('male_70_74')->comment('70-74歳の男性');
            $table->unsignedInteger('female_70_74')->comment('70-74歳の女性');
            $table->unsignedInteger('male_75_79')->comment('75-79歳の男性');
            $table->unsignedInteger('female_75_79')->comment('75-79歳の女性');
            $table->unsignedInteger('male_80_84')->comment('80-84歳の男性');
            $table->unsignedInteger('female_80_84')->comment('80-84歳の女性');
            $table->unsignedInteger('male_85_plus')->comment('85歳以上の男性'); // 85歳以上の男性
            $table->unsignedInteger('female_85_plus')->comment('85歳以上の女性'); // 85歳以上の女性
            $table->unsignedInteger('households_count')->comment('世帯数');
            $table->text('remarks')->nullable()->comment('備考'); // 備考はテキストエリアにする可能性

            // ユニーク制約 (同じ自治体、同じ調査年月日、同じ地域コードのデータは1つだけ)
            $table->unique(['municipality_code', 'survey_date', 'region_code'], 'unique_population_data');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('population_statistics');
    }
};
