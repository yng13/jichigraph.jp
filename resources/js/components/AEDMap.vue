<template>
    <div class="h-[500px] w-full relative">
        <l-map :zoom="zoom" :center="center" :use-global-leaflet="false">
            <l-tile-layer url="https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png"
                          attribution="&copy; OpenStreetMap contributors"></l-tile-layer>

            <l-marker v-for="poi in aedLocations" :key="poi.id" :lat-lng="[poi.latitude, poi.longitude]">
                <l-popup>
                    <div class="font-sans text-sm">
                        <h3 class="mt-0 mb-1 text-gray-800 text-base font-semibold">{{ poi.name }}</h3>
                        <p class="mb-0.5"><strong>住所:</strong> {{ poi.address }}</p>
                        <p v-if="poi.details && poi.details.availability_remarks" class="mb-0.5">
                            <strong>利用可能日時:</strong> {{ poi.details.availability_remarks }}
                        </p>
                        <p v-if="poi.details && poi.details.child_friendly !== null" class="mb-0.5">
                            <strong>小児対応:</strong> {{ poi.details.child_friendly ? 'あり' : 'なし' }}
                        </p>
                        <p v-if="poi.phone_number" class="mb-0.5">
                            <strong>電話:</strong> {{ poi.phone_number }}
                            <span v-if="poi.details && poi.details.extension_number"> (内線: {{
                                    poi.details.extension_number
                                }})</span>
                        </p>
                        <p v-if="poi.remarks" class="mb-0.5">
                            <strong>備考:</strong> {{ poi.remarks }}
                        </p>
                        <p v-if="poi.details && poi.details.installation_position" class="mb-0.5">
                            <strong>設置位置:</strong> {{ poi.details.installation_position }}
                        </p>
                    </div>
                </l-popup>
            </l-marker>
        </l-map>
    </div>
</template>

<script>
import {LMap, LTileLayer, LMarker, LPopup} from '@vue-leaflet/vue-leaflet';
import axios from 'axios';

export default {
    components: {
        LMap,
        LTileLayer,
        LMarker,
        LPopup,
    },
    data() {
        return {
            zoom: 13,
            center: [35.807670774844, 139.724133736802], // 川口市役所の緯度・経度を初期中心に設定
            aedLocations: [],
        };
    },
    async created() {
        try {
            // APIからデータを取得
            // 川口市の全国地方公共団体コード: 112038
            const response = await axios.get('https://dev.jichigraph.jp/api/pois', {
                params: {
                    type: 'AED',
                    municipality_code: '112038'
                }
            });
            this.aedLocations = response.data;
            console.log('AEDデータ取得:', this.aedLocations);
        } catch (error) {
            console.error('AEDデータの取得に失敗しました:', error);
        }
    },
};
</script>

<style scoped>
/* Leafletのデフォルトマーカーアイコンが表示されない場合の対処 */
/* Vue-Leafletを使用している場合、WebpackやViteの環境でアイコンパスが正しく解決されないことがあるため、
   ここに必要な画像を直接指定します。 */
/* node_modules/leaflet/dist/images/marker-icon.png */
/* node_modules/leaflet/dist/images/marker-shadow.png */
/* Vite環境では、`public` フォルダなどに画像をコピーし、そのパスを指定するのが一般的です。 */
/* 例えば、public/images/marker-icon.png にコピーした場合: */
/*
.leaflet-default-icon-path {
  background-image: url('/images/marker-icon.png');
}
.leaflet-pane .leaflet-shadow {
  background-image: url('/images/marker-shadow.png');
}
*/
/* もしデフォルトの青いマーカーが表示されない場合は、上記を参考に設定してください。
   現在のスクリーンショットでは表示されているので、一旦このまま進めます。 */
</style>
