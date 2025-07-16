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
        outDir: path.resolve(__dirname, "public/build"), // Tempatkan hasil build sementara
        rollupOptions: {
            input: "resources/js/app.js",
        },
        emptyOutDir: true, // Bersihkan folder build sebelumnya
        afterBuild: () => {
            fs.renameSync(
                path.resolve(__dirname, "public/build"),
                path.resolve(__dirname, "public_html/build")
            );
        },
    },
});
