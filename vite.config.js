import { defineConfig } from "vite";
import laravel from "laravel-vite-plugin";
import { bunny } from "laravel-vite-plugin/fonts";
import tailwindcss from "@tailwindcss/vite";

export default defineConfig({
    plugins: [
        laravel({
            input: [
                "resources/css/app.css",
                "resources/css/filament/admin/theme.css",
                "resources/js/app.js",
                "resources/js/passkeys.js",
            ],
            refresh: true,
            fonts: [
                bunny("Instrument Sans", {
                    alias: "body",
                    variable: "--font-brand-sans",
                    weights: [400, 500, 600, 700],
                    styles: ["normal"],
                    display: "swap",
                    preload: [{ weight: 400 }, { weight: 600 }],
                    fallbacks: ["ui-sans-serif", "system-ui", "sans-serif"],
                }),
                bunny("Lora", {
                    alias: "display",
                    variable: "--font-brand-display",
                    weights: [500, 600, 700],
                    styles: ["normal"],
                    display: "swap",
                    preload: [{ weight: 600 }],
                    fallbacks: ["Georgia", "Cambria", "serif"],
                }),
            ],
        }),
        tailwindcss(),
    ],
    server: {
        cors: true,
        watch: {
            ignored: ["**/storage/framework/views/**"],
        },
    },
});
