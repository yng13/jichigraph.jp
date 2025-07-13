<template>
    <div v-if="issue" class="fixed inset-0 bg-gray-600 bg-opacity-75 flex items-center justify-center z-50 p-4" @click.self="closeModal">
        <div class="bg-white rounded-lg shadow-xl max-w-2xl w-full max-h-[90vh] overflow-y-auto relative p-6">
            <button @click="closeModal" class="absolute top-3 right-3 text-gray-500 hover:text-gray-700 text-2xl font-bold">&times;</button>

            <h3 class="text-3xl font-extrabold text-gray-900 mb-4">{{ issue.title }}</h3>
            <p class="text-gray-700 text-lg mb-4">{{ issue.description }}</p>

            <div class="grid grid-cols-2 gap-4 text-sm text-gray-600 mb-6">
                <div>
                    <p><strong>自治体:</strong> {{ issue.municipality_name || issue.municipality_code }}</p>
                    <p><strong>ステータス:</strong> <span :class="getStatusClass(issue.status)">{{ getStatusText(issue.status) }}</span></p>
                </div>
                <div>
                    <p><strong>投稿日時:</strong> {{ new Date(issue.created_at).toLocaleDateString() }}</p>
                    <p><strong>位置:</strong> 緯度 {{ issue.latitude.toFixed(6) }}, 経度 {{ issue.longitude.toFixed(6) }}</p>
                </div>
            </div>

            <div v-if="issue.image_url" class="mb-6">
                <img :src="issue.image_url" alt="課題画像" class="w-full h-auto rounded-lg shadow-md">
            </div>

            <hr class="my-6 border-gray-300">

            <h4 class="text-2xl font-bold text-gray-800 mb-4">解決策</h4>
            <div v-if="solutions.length === 0 && !isLoadingSolutions" class="text-gray-600 mb-4">
                まだ解決策は投稿されていません。
            </div>
            <div v-if="isLoadingSolutions" class="text-center text-gray-500 mb-4">
                解決策を読み込み中...
            </div>
            <div v-else class="space-y-4 mb-6">
                <div v-for="solution in solutions" :key="solution.id" class="border border-gray-200 rounded-lg p-4 bg-gray-50">
                    <p class="text-gray-800 mb-2">{{ solution.content }}</p>
                    <div class="flex justify-between items-center text-xs text-gray-500">
            <span>
              投稿: {{ new Date(solution.created_at).toLocaleDateString() }}
            </span>
                        <span class="flex items-center">
              <svg class="w-4 h-4 mr-1 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 10h4.764a2 2 0 011.789 2.894l-3.5 7A2 2 0 0115.263 21H3.737a2 2 0 01-1.789-2.894l3.5-7A2 2 0 018.736 10H14zm0 0V9a2 2 0 00-2-2h-3a2 2 0 00-2 2v1m9 0a2 2 0 002 2v4a2 2 0 01-2 2H3"></path></svg>
              {{ solution.votes }}
            </span>
                    </div>
                </div>
            </div>

            <hr class="my-6 border-gray-300">

            <h4 class="text-2xl font-bold text-gray-800 mb-4">解決策を提案する</h4>
            <form @submit.prevent="submitSolution">
                <div class="mb-4">
                    <label for="solutionContent" class="block text-gray-700 text-sm font-bold mb-2">解決策の内容 <span class="text-red-500">*</span></label>
                    <textarea id="solutionContent" v-model="solutionForm.content" rows="4"
                              class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                              required placeholder="課題に対する解決策を具体的に入力してください"></textarea>
                </div>
                <div class="flex items-center justify-between">
                    <button type="submit"
                            class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                        解決策を投稿する
                    </button>
                    <div v-if="solutionSubmissionStatus === 'submitting'" class="text-green-500">送信中...</div>
                    <div v-if="solutionSubmissionStatus === 'success'" class="text-green-500">解決策を投稿しました！</div>
                    <div v-if="solutionSubmissionStatus === 'error'" class="text-red-500">投稿に失敗しました: {{ solutionErrorMessage }}</div>
                </div>
            </form>
        </div>
    </div>
</template>

<script>
import axios from 'axios';

export default {
    props: {
        issue: {
            type: Object,
            required: true // 親から渡される課題オブジェクト
        }
    },
    emits: ['close', 'solution-posted'], // 親に通知するイベント

    data() {
        return {
            solutions: [],
            isLoadingSolutions: true,
            solutionForm: {
                content: '',
            },
            solutionSubmissionStatus: null, // null, 'submitting', 'success', 'error'
            solutionErrorMessage: '',
        };
    },
    watch: {
        // issue プロップが変更されたら（別の課題が選択されたら）、解決策を再フェッチ
        issue: {
            immediate: true, // コンポーネント初期化時にも実行
            handler(newIssue) {
                if (newIssue) {
                    this.fetchSolutions();
                } else {
                    this.solutions = []; // 課題がない場合はクリア
                }
            }
        }
    },
    methods: {
        closeModal() {
            this.$emit('close'); // 親コンポーネントに閉じるイベントを通知
        },
        async fetchSolutions() {
            this.isLoadingSolutions = true;
            try {
                const response = await axios.get(`https://dev.jichigraph.jp/api/issues/${this.issue.id}/solutions`);
                this.solutions = response.data;
                console.log('解決策データ取得:', this.solutions);
            } catch (error) {
                console.error('解決策一覧の取得に失敗しました:', error);
                this.solutions = [];
            } finally {
                this.isLoadingSolutions = false;
            }
        },
        async submitSolution() {
            this.solutionSubmissionStatus = 'submitting';
            this.solutionErrorMessage = '';

            try {
                const response = await axios.post(`https://dev.jichigraph.jp/api/issues/${this.issue.id}/solutions`, this.solutionForm);
                this.solutionSubmissionStatus = 'success';
                console.log('解決策投稿成功:', response.data);
                this.solutionForm.content = ''; // フォームをリセット
                await this.fetchSolutions(); // 解決策リストを再読み込み
                this.$emit('solution-posted'); // 親に解決策が投稿されたことを通知

                setTimeout(() => { this.solutionSubmissionStatus = null; }, 3000); // 3秒後にメッセージをクリア

            } catch (error) {
                this.solutionSubmissionStatus = 'error';
                if (error.response && error.response.data && error.response.data.errors) {
                    this.solutionErrorMessage = Object.values(error.response.data.errors).flat().join(' ');
                } else if (error.response && error.response.data && error.response.data.message) {
                    this.solutionErrorMessage = error.response.data.message;
                } else {
                    this.solutionErrorMessage = 'ネットワークエラー、またはサーバーからの予期せぬ応答です。';
                }
                console.error('解決策投稿失敗:', error);
            }
        },
        getStatusClass(status) {
            switch (status) {
                case 'open': return 'bg-red-100 text-red-800 px-2 py-0.5 rounded';
                case 'in_progress': return 'bg-yellow-100 text-yellow-800 px-2 py-0.5 rounded';
                case 'resolved': return 'bg-green-100 text-green-800 px-2 py-0.5 rounded';
                default: return 'bg-gray-100 text-gray-800 px-2 py-0.5 rounded';
            }
        },
        getStatusText(status) {
            switch (status) {
                case 'open': return '未解決';
                case 'in_progress': return '対応中';
                case 'resolved': return '解決済み';
                default: return '不明';
            }
        },
    },
};
</script>

<style scoped>
/* Tailwind CSSを使用しているため、カスタムCSSは最小限 */
/* モーダルのオーバーレイとコンテンツのz-indexはTailwindでも可能だが、固定値で指定 */
</style>
