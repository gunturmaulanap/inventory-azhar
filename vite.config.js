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
        outDir: path.resolve(__dirname, "../public_html/build"), // ke public_html/build
        emptyOutDir: true, // Bersihkan folder build sebelum build baru
    },
});
