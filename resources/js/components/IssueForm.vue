<template>
    <div class="max-w-2xl mx-auto p-6 bg-white shadow-lg rounded-lg border border-gray-200">
        <h3 class="text-2xl font-bold text-gray-800 mb-6 text-center">地域の課題を投稿</h3>

        <form @submit.prevent="submitIssue">
            <div class="mb-4">
                <label for="title" class="block text-gray-700 text-sm font-bold mb-2">タイトル <span
                    class="text-red-500">*</span></label>
                <input type="text" id="title" v-model="form.title"
                       class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                       required placeholder="課題のタイトルを入力してください">
            </div>

            <div class="mb-4">
                <label for="description" class="block text-gray-700 text-sm font-bold mb-2">詳細 <span
                    class="text-red-500">*</span></label>
                <textarea id="description" v-model="form.description" rows="4"
                          class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                          required placeholder="課題の詳細を具体的に入力してください"></textarea>
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">課題の位置 <span class="text-red-500">*</span></label>
                <p class="text-sm text-gray-600 mb-2">地図をクリックして、課題の位置を選択してください。</p>
                <div class="h-[300px] w-full border border-gray-300 rounded overflow-hidden relative">
                    <l-map :zoom="mapZoom" :center="mapCenter" :use-global-leaflet="false" @click="onMapClick">
                        <l-tile-layer url="https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png"
                                      attribution="&copy; OpenStreetMap contributors"></l-tile-layer>
                        <l-marker v-if="form.latitude && form.longitude"
                                  :lat-lng="[form.latitude, form.longitude]"></l-marker>
                    </l-map>
                </div>
                <p class="text-sm text-gray-600 mt-2">
                    緯度: {{ form.latitude ? form.latitude.toFixed(6) : '未選択' }} / 経度:
                    {{ form.longitude ? form.longitude.toFixed(6) : '未選択' }}
                </p>
            </div>

            <div class="mb-4">
                <label for="municipality_code" class="block text-gray-700 text-sm font-bold mb-2">自治体コード <span
                    class="text-red-500">*</span></label>
                <input type="text" id="municipality_code" v-model="form.municipality_code"
                       class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                       required placeholder="例: 112038 (川口市)">
            </div>

            <div class="mb-6">
                <label for="image_url" class="block text-gray-700 text-sm font-bold mb-2">画像URL (任意)</label>
                <input type="url" id="image_url" v-model="form.image_url"
                       class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                       placeholder="課題に関連する画像のURL">
            </div>

            <div class="flex items-center justify-between">
                <button type="submit"
                        class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                    課題を投稿する
                </button>
                <div v-if="submissionStatus === 'submitting'" class="text-blue-500">送信中...</div>
                <div v-if="submissionStatus === 'success'" class="text-green-500">投稿しました！</div>
                <div v-if="submissionStatus === 'error'" class="text-red-500">投稿に失敗しました: {{
                        errorMessage
                    }}
                </div>
            </div>
        </form>
    </div>
</template>

<script>
import {LMap, LTileLayer, LMarker} from '@vue-leaflet/vue-leaflet';
import axios from 'axios';

export default {
    components: {
        LMap,
        LTileLayer,
        LMarker,
    },
    data() {
        return {
            form: {
                title: '',
                description: '',
                municipality_code: '112038', // デフォルトで川口市のコードを設定
                latitude: null,
                longitude: null,
                image_url: '',
            },
            mapZoom: 13,
            mapCenter: [35.807670774844, 139.724133736802], // 川口市役所の緯度・経度
            submissionStatus: null, // null, 'submitting', 'success', 'error'
            errorMessage: '',
        };
    },
    methods: {
        onMapClick(event) {
            // 地図クリックで緯度・経度を更新
            this.form.latitude = event.latlng.lat;
            this.form.longitude = event.latlng.lng;
        },
        async submitIssue() {
            this.submissionStatus = 'submitting';
            this.errorMessage = '';

            try {
                const response = await axios.post('https://dev.jichigraph.jp/api/issues', this.form);
                this.submissionStatus = 'success';
                console.log('課題投稿成功:', response.data);
                // フォームをリセット
                this.form = {
                    title: '',
                    description: '',
                    municipality_code: '112038',
                    latitude: null,
                    longitude: null,
                    image_url: '',
                };
                setTimeout(() => {
                    this.submissionStatus = null;
                }, 3000); // 3秒後にメッセージをクリア

            } catch (error) {
                this.submissionStatus = 'error';
                if (error.response && error.response.data && error.response.data.errors) {
                    // バリデーションエラーの場合
                    this.errorMessage = Object.values(error.response.data.errors).flat().join(' ');
                } else if (error.response && error.response.data && error.response.data.message) {
                    // その他のAPIエラーメッセージ
                    this.errorMessage = error.response.data.message;
                } else {
                    this.errorMessage = 'ネットワークエラー、またはサーバーからの予期せぬ応答です。';
                }
                console.error('課題投稿失敗:', error);
            }
        },
    },
};
</script>

<style scoped>
/* Tailwind CSSを使用しているため、カスタムCSSは最小限にするか、不要 */
/* Leafletのマーカーアイコン表示問題の対処はAEDMap.vueのコメント参照 */
</style>
