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
        manifest: true,
        outDir: path.resolve(__dirname, "/home/azha3438/public_html/build"), // Tempatkan hasil build di /home/azha3438/public_html/build
        rollupOptions: {
            input: "resources/js/app.js",
        },
        emptyOutDir: true, // Bersihkan isi folder build sebelum build baru
    },
});
