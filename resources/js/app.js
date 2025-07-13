import './bootstrap';

import {createApp} from 'vue';
import {LMap, LTileLayer, LMarker, LPopup} from '@vue-leaflet/vue-leaflet'; // 追加
import 'leaflet/dist/leaflet.css'; // LeafletのCSSをインポート

const app = createApp({});

// 地図コンポーネントをグローバルに登録（または必要なコンポーネント内でインポート）
app.component('LMap', LMap);
app.component('LTileLayer', LTileLayer);
app.component('LMarker', LMarker);
app.component('LPopup', LPopup);

import ExampleComponent from './components/ExampleComponent.vue';
app.component('example-component', ExampleComponent);

// ここにAEDMapコンポーネントのインポートと登録を追加してください
import AEDMap from './components/AEDMap.vue'; // 追加
app.component('aed-map', AEDMap); // 追加: コンポーネント名を kebab-case で登録



app.mount('#app');
