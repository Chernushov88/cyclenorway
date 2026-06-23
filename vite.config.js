import { defineConfig } from 'vite'
import ThemeConfig from './wp-content/themes/profi.dev/vite.config.js'
import fs from "fs";

export default defineConfig({
    ...ThemeConfig,
    server: {
        cors: true,
        fs: { strict: false },

        strictPort: true,
        port: process.env.DOCKER_VITE_PORT || 3001,

        https: {
            key: fs.readFileSync("_docker/nginx/ssl/nginx-selfsigned.key"),
            cert: fs.readFileSync("_docker/nginx/ssl/nginx-selfsigned.crt"),
        },

        origin: "https://localhost:" + (process.env.DOCKER_VITE_PORT || 3001)
    }
})