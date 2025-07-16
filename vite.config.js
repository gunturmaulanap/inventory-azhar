import { defineConfig } from "vite";
import laravel from "laravel-vite-plugin";
import path from "path";

export default defineConfig({
    plugins: [
        laravel({
            input: ["resources/css/app.css", "resources/js/app.js"],
            refresh: true,
            buildDirectory: "build", // Laravel akan cari manifest di public/build
        }),
    ],
    build: {
        outDir: path.resolve(__dirname, "../public_html/build"), // ini memastikan file build ditempatkan di public_html/build
    },
});
