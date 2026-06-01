import { defineConfig } from "vite";
import laravel from "laravel-vite-plugin";
import tailwindcss from "@tailwindcss/vite";

export default defineConfig({
    plugins: [
        laravel({
            input: ["resources/css/app.css", "resources/js/app.js"],
            refresh: true,
        }),

        tailwindcss(),
    ],

    build: {
        manifest: "manifest.json",
        outDir: "public/build",
        emptyOutDir: true,
    },

    server: {
        host: "127.0.0.1",
        port: 5173,
        strictPort: true,
        watch: {
            ignored: ["**/storage/framework/views/**"],
        },
    },
});
