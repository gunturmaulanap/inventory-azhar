import fs from "fs";
import path from "path";

export default defineConfig({
    plugins: [
        laravel({
            input: ["resources/css/app.css", "resources/js/app.js"],
            refresh: true,
        }),
    ],
    build: {
        manifest: true,
        outDir: path.resolve(__dirname, "../public_html/build"), // Pastikan path ini menuju ke folder public_html/build
        rollupOptions: {
            input: "resources/js/app.js",
        },
        emptyOutDir: true, // Bersihkan folder build sebelumnya
    },
});
