import { defineConfig, globalIgnores } from "eslint/config"

import globals from "globals"
import js from "@eslint/js"

export default defineConfig([
    js.configs.recommended,
    {
        languageOptions: {
            globals: {
                ...globals.browser,
                ...globals.node,
            },

            ecmaVersion: "latest",
            sourceType: "module",
            parserOptions: {},
        },
        rules: {
            indent: ["error", "tab"],
            "linebreak-style": ["error", "unix"],
            quotes: ["error", "double"],
            semi: ["error", "never"],
            "space-before-function-paren": ["error", "always"],
            "no-multi-spaces": ["error", {}],
            "space-in-parens": ["error", "never"],

            "comma-spacing": ["error", {
                before: false,
                after: true,
            }],

            curly: ["error", "all"],

            "object-curly-newline": ["error", { multiline: true, }],

            "object-curly-spacing": ["error", "always"],
        },
    },
    { files: ["**/*.jsx", "**/*.js"] },
    globalIgnores([
        "**/node_modules/", "**/vendor/", "wp-content/plugins", "eslint.config.js",
        "vite.config.js", "wp-content/mu-plugins", "wp-content/themes/profi.dev/build",
        "wp-content/themes/profi.dev/vite.config.js"
    ])
])
