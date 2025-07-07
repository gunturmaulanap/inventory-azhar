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
        outDir: path.resolve(__dirname, "public_html/build"), // arahkan hasil build ke public_html
        rollupOptions: {
            input: "resources/js/app.js",
        },
        emptyOutDir: true, // bersihkan isi folder build sebelum build baru
    },
});
