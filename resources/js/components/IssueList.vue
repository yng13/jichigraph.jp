<template>
    <div class="bg-white shadow-lg rounded-lg p-6 mb-6">
        <h3 class="text-2xl font-bold text-gray-800 mb-6 text-center">地域の課題一覧</h3>

        <div v-if="issues.length === 0 && !isLoading" class="text-center text-gray-600">
            課題はまだありません。新しい課題を投稿してみましょう！
        </div>

        <div v-if="isLoading" class="text-center text-gray-500">
            課題を読み込み中...
        </div>

        <div v-else class="space-y-4">
            <div v-for="issue in issues" :key="issue.id"
                 class="border border-gray-200 rounded-lg p-4 cursor-pointer hover:bg-gray-50 transition duration-150"
                 @click="selectIssue(issue)">
                <h4 class="text-lg font-semibold text-blue-700 mb-1">{{ issue.title }}</h4>
                <p class="text-gray-600 text-sm mb-2 line-clamp-2">{{ issue.description }}</p>
                <div class="flex justify-between items-center text-xs text-gray-500">
          <span>
            <span v-if="issue.municipality_name">{{ issue.municipality_name }} ({{ issue.municipality_code }})</span>
            <span v-else>地域: {{ issue.municipality_code }}</span>
            | 投稿: {{ new Date(issue.created_at).toLocaleDateString() }}
          </span>
                    <span :class="getStatusClass(issue.status)">{{ getStatusText(issue.status) }}</span>
                </div>
            </div>
        </div>
    </div>

    <IssueDetailModal
        v-if="selectedIssue"
        :issue="selectedIssue"
        @close="selectedIssue = null"
        @solution-posted="handleSolutionPosted"
    />
</template>

<script>
import axios from 'axios';
import IssueDetailModal from './IssueDetailModal.vue'; // 後で作成するモーダルコンポーネント

export default {
    components: {
        IssueDetailModal,
    },
    data() {
        return {
            issues: [],
            isLoading: true,
            selectedIssue: null, // クリックされた課題を保持
        };
    },
    async created() {
        await this.fetchIssues();
    },
    methods: {
        async fetchIssues() {
            this.isLoading = true;
            try {
                // 川口市の課題のみを取得
                const response = await axios.get('https://dev.jichigraph.jp/api/issues', {
                    params: {
                        municipality_code: '112038'
                    }
                });
                this.issues = response.data;
            } catch (error) {
                console.error('課題一覧の取得に失敗しました:', error);
            } finally {
                this.isLoading = false;
            }
        },
        selectIssue(issue) {
            this.selectedIssue = issue; // 課題が選択されたらモーダルに渡す
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
        handleSolutionPosted() {
            // 解決策が投稿されたら、必要に応じて課題リストを再読み込みするなど
            // 現時点では何もしないが、将来的に課題の状態（例: 解決策数など）を更新する際に使う
        }
    },
};
</script>

<style scoped>
/* Tailwind CSSを使用しているため、カスタムCSSは最小限 */
/* line-clamp-2 は tailwind/line-clamp プラグインが必要になる場合があります */
</style>
