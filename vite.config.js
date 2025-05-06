import {defineConfig} from "vite";
import laravel from "laravel-vite-plugin";
import tailwindcss from "@tailwindcss/vite";

import fs from "fs";

export default defineConfig({
    plugins: [
        laravel({
            input: [
                "resources/css/app.css",
                "resources/js/app.js",
                //'resources/**/**.blade.php',
            ],
            refresh: true,
        }),
        tailwindcss(),
    ],
    server: {
        host: "dev.jichigraph.jp",
        //https: true,
        https: {
            key: fs.readFileSync("/var/www/html/jichigraph.jp/jichigraph.key"),
            cert: fs.readFileSync("/var/www/html/jichigraph.jp/jichigraph.crt"),
        },
        hmr: {
            host: "dev.jichigraph.jp",
            protocol: "wss",
        },
        cors: true,
    },
    build: {
        rollupOptions: {
            output: {
                manualChunks: {
                    uiLibs: [
                        'dropzone',
                        'flatpickr',
                        'fullcalendar',
                        'jsvectormap',
                        'swiper',
                    ],
                    apexcharts: [
                        'apexcharts',
                    ],
                    misc: [

                    ],
                },
            },
        },
    },
});
