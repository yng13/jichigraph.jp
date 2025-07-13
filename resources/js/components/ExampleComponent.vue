<template>
    <div class="container mx-auto p-4">
        <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
            <div class="col-span-1">
                <div class="bg-white shadow-md rounded-lg p-4 mb-6">
                    <h2 class="text-2xl font-bold text-gray-800 mb-4">川口市 AED 設置場所</h2>
                    <aed-map></aed-map>
                </div>
                <div class="bg-white shadow-md rounded-lg p-4">
                    <h2 class="text-2xl font-bold text-gray-800 mb-4">川口市 人口統計データ</h2>
                    <population-chart></population-chart>
                </div>
            </div>

            <div class="col-span-1">
                <div class="bg-white shadow-md rounded-lg p-4 mb-6">
                    <issue-form></issue-form>
                </div>
                <issue-list></issue-list>
            </div>
        </div>
    </div>
</template>

<script>
import AEDMap from './AEDMap.vue';
import PopulationChart from './PopulationChart.vue';
import IssueForm from './IssueForm.vue';
import IssueList from './IssueList.vue'; // IssueListをインポート

export default {
    components: {
        AEDMap,
        PopulationChart,
        IssueForm,
        IssueList, // コンポーネントとして登録
    },
    mounted() {
        console.log('ExampleComponent mounted.');
    },
};
</script>

<style scoped>
/* Tailwind CSSを使用しているため、カスタムCSSは不要 */
</style>
