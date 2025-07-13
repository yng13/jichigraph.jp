<template>
    <div class="max-w-4xl mx-auto p-4 border border-gray-200 rounded-lg bg-white shadow-md relative h-[500px]">
        <h3 class="text-center mb-4 text-xl font-semibold text-gray-700">川口市 年齢層別人口構成 ({{ surveyDate }})</h3>
        <canvas ref="populationChart"></canvas>
    </div>
</template>

<script>
import Chart from 'chart.js/auto';
import axios from 'axios';
import {isNumeric, excelSerialDateToJSDate} from '../utils/dateConverter';

export default {
    data() {
        return {
            chartInstance: null,
            populationData: null,
            surveyDate: 'データ取得中...',
        };
    },
    async mounted() {
        await this.fetchAndRenderChart();
    },
    methods: {
        async fetchAndRenderChart() {
            try {
                const response = await axios.get('https://dev.jichigraph.jp/api/population-statistics', {
                    params: {
                        municipality_code: '112038',
                        region_code: '0',
                    }
                });
                const allData = response.data;

                if (allData.length === 0) {
                    console.warn('人口統計データが見つかりませんでした。');
                    return;
                }

                allData.sort((a, b) => {
                    const dateA = this.parseSurveyDate(a.survey_date);
                    const dateB = this.parseSurveyDate(b.survey_date);
                    return dateB.getTime() - dateA.getTime();
                });

                this.populationData = allData[0];
                this.surveyDate = this.formatSurveyDate(this.populationData.survey_date);

                this.renderChart();
            } catch (error) {
                console.error('人口統計データの取得に失敗しました:', error);
            }
        },

        parseSurveyDate(dateString) {
            if (isNumeric(dateString)) {
                return excelSerialDateToJSDate(parseInt(dateString, 10));
            } else if (dateString) {
                return new Date(dateString);
            }
            return new Date();
        },

        formatSurveyDate(dateValue) {
            if (isNumeric(dateValue)) {
                const jsDate = excelSerialDateToJSDate(parseInt(dateValue, 10));
                return `${jsDate.getFullYear()}年${jsDate.getMonth() + 1}月${jsDate.getDate()}日`;
            } else if (dateValue) {
                const parts = dateValue.split('/');
                if (parts.length === 3) {
                    return `${parts[0]}年${parts[1]}月${parts[2]}日`;
                }
                return dateValue;
            }
            return '不明';
        },

        renderChart() {
            if (!this.populationData) return;

            const labels = [
                '0-4歳', '5-9歳', '10-14歳', '15-19歳', '20-24歳',
                '25-29歳', '30-34歳', '35-39歳', '40-44歳', '45-49歳',
                '50-54歳', '55-59歳', '60-64歳', '65-69歳', '70-74歳',
                '75-79歳', '80-84歳', '85歳以上'
            ];

            const maleData = labels.map((label) => {
                const key = `male_${label.replace('-', '_').replace('歳', '').replace('以上', '_plus')}`;
                return this.populationData[key];
            });

            const femaleData = labels.map((label) => {
                const key = `female_${label.replace('-', '_').replace('歳', '').replace('以上', '_plus')}`;
                return this.populationData[key];
            });

            if (this.chartInstance) {
                this.chartInstance.destroy();
            }

            const ctx = this.$refs.populationChart.getContext('2d');
            this.chartInstance = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [
                        {
                            label: '男性人口',
                            backgroundColor: 'rgba(54, 162, 235, 0.5)',
                            borderColor: 'rgba(54, 162, 235, 1)',
                            borderWidth: 1,
                            data: maleData,
                        },
                        {
                            label: '女性人口',
                            backgroundColor: 'rgba(255, 99, 132, 0.5)',
                            borderColor: 'rgba(255, 99, 132, 1)',
                            borderWidth: 1,
                            data: femaleData,
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        x: {
                            title: {
                                display: true,
                                text: '年齢層'
                            }
                        },
                        y: {
                            beginAtZero: true,
                            title: {
                                display: true,
                                text: '人口数'
                            }
                        }
                    },
                    plugins: {
                        legend: {
                            display: true,
                        },
                        title: {
                            display: true,
                            text: '年齢層別 人口構成'
                        }
                    }
                }
            });
        },
    },
};
</script>

<style scoped>
/* カスタムCSSは全て削除 */
/* canvas要素のサイズは親コンテナに依存するため、canvas要素には直接width/heightを指定しません */
</style>
