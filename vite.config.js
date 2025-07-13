import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue'; // この行を追加
import tailwindcss from '@tailwindcss/vite';
import basicSsl from '@vitejs/plugin-basic-ssl'
import fs from 'fs';

export default defineConfig({
    plugins: [
        basicSsl(),
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
        vue({ // このブロックを追加
            template: {
                transformAssetUrls: {
                    base: null,
                    includeAbsolute: false,
                },
            },
        }),
        tailwindcss(),
    ],
    resolve: { // このブロックを追加
        alias: {
            vue: 'vue/dist/vue.esm-bundler.js',
        },
    },
    server: {
        host: 'dev.jichigraph.jp',
        https: {
            key: fs.readFileSync('./jichigraph.key'),
            cert: fs.readFileSync('./jichigraph.crt'),
        },
    }
});
