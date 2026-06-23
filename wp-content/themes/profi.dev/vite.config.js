import { defineConfig } from 'vite'
import alias from "@rollup/plugin-alias"
import autoprefixer from "autoprefixer"
import path from "path"
import * as glob from "glob";
import {fileURLToPath, URL} from "url";

export default defineConfig({
    base: './',
    plugins: [
        alias()
    ],
    optimizeDeps: {
        include: ['gsap'],
    },
    css: {
        postcss: {
            plugins: [
                autoprefixer({})
            ],
        }
    },
    resolve: {
        alias: [
            {
                find: "~css",
                replacement: fileURLToPath(new URL(path.join(__dirname + "/assets/css/"), import.meta.url)),
            },
            {
                find: "~fonts",
                replacement: fileURLToPath(new URL(path.join(__dirname + "/assets/fonts/"), import.meta.url)),
            },
            {
                find: "~js",
                replacement: fileURLToPath(new URL(path.join(__dirname + "/assets/js/"), import.meta.url)),
            },
            {
                find: "~assets",
                replacement: fileURLToPath(new URL(path.join(__dirname + "/assets/"), import.meta.url)),
            }
        ]
    },
    build: {
        outDir: path.resolve(__dirname, "./build"),
        emptyOutDir: true,
        manifest: true,
        target: "es2021",
        rollupOptions: {
            input: glob
                .sync(path.join(__dirname + "/blocks/**/*.{css,js,scss}"))
                .concat(glob.sync(path.join(__dirname + "/assets/js/single*.js")))
                .concat(glob.sync(path.join(__dirname + "/assets/css/single*.scss")))
                .concat([
                    path.join(__dirname + "/assets/js/scripts.js"),
                    path.join(__dirname + "/assets/css/admin.scss"),
                    path.join(__dirname + "/assets/css/style.scss"),
                    path.join(__dirname + "/assets/css/style-editor.scss"),
                    path.join(__dirname + "/assets/css/_constants.scss"),
                    path.join(__dirname + "/assets/css/_constants_editor.scss"),
                    path.join(__dirname + "/assets/css/blocks.scss"),
                    path.join(__dirname + "/assets/js/blocks.js"),
                ])
        },
        minify: "esbuild",
        write: true,
    }
})
