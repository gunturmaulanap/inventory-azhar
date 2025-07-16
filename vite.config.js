import { defineConfig } from "vite";
import laravel from "laravel-vite-plugin";
import path from "path";

export default defineConfig({
    plugins: [
        laravel({
            input: ["resources/css/app.css", "resources/js/app.js"],
            refresh: true,
            buildDirectory: "build", // Laravel akan mencari manifest di public/build
        }),
    ],
    build: {
        outDir: "public/build",
        manifest: true,
    },
    server: {
        proxy: {
            "/": "http://yourproductiondomain.com", // Ganti dengan domain produksi Anda
        },
    },
});
