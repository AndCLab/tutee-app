// vite.config.js
import { defineConfig } from "file:///C:/Users/Asus/Desktop/VS%20CODE/laravel/tutee-app/node_modules/vite/dist/node/index.js";
import laravel from "file:///C:/Users/Asus/Desktop/VS%20CODE/laravel/tutee-app/node_modules/laravel-vite-plugin/dist/index.js";
var vite_config_default = defineConfig({
  plugins: [
    laravel({
      input: ["resources/css/app.css", "resources/js/app.js"],
      refresh: true
    })
  ]
});
export {
  vite_config_default as default
};
//# sourceMappingURL=data:application/json;base64,ewogICJ2ZXJzaW9uIjogMywKICAic291cmNlcyI6IFsidml0ZS5jb25maWcuanMiXSwKICAic291cmNlc0NvbnRlbnQiOiBbImNvbnN0IF9fdml0ZV9pbmplY3RlZF9vcmlnaW5hbF9kaXJuYW1lID0gXCJDOlxcXFxVc2Vyc1xcXFxBc3VzXFxcXERlc2t0b3BcXFxcVlMgQ09ERVxcXFxsYXJhdmVsXFxcXHR1dGVlLWFwcFwiO2NvbnN0IF9fdml0ZV9pbmplY3RlZF9vcmlnaW5hbF9maWxlbmFtZSA9IFwiQzpcXFxcVXNlcnNcXFxcQXN1c1xcXFxEZXNrdG9wXFxcXFZTIENPREVcXFxcbGFyYXZlbFxcXFx0dXRlZS1hcHBcXFxcdml0ZS5jb25maWcuanNcIjtjb25zdCBfX3ZpdGVfaW5qZWN0ZWRfb3JpZ2luYWxfaW1wb3J0X21ldGFfdXJsID0gXCJmaWxlOi8vL0M6L1VzZXJzL0FzdXMvRGVza3RvcC9WUyUyMENPREUvbGFyYXZlbC90dXRlZS1hcHAvdml0ZS5jb25maWcuanNcIjtpbXBvcnQgeyBkZWZpbmVDb25maWcgfSBmcm9tIFwidml0ZVwiO1xuaW1wb3J0IGxhcmF2ZWwgZnJvbSBcImxhcmF2ZWwtdml0ZS1wbHVnaW5cIjtcblxuZXhwb3J0IGRlZmF1bHQgZGVmaW5lQ29uZmlnKHtcbiAgICBwbHVnaW5zOiBbXG4gICAgICAgIGxhcmF2ZWwoe1xuICAgICAgICAgICAgaW5wdXQ6IFtcInJlc291cmNlcy9jc3MvYXBwLmNzc1wiLCBcInJlc291cmNlcy9qcy9hcHAuanNcIl0sXG4gICAgICAgICAgICByZWZyZXNoOiB0cnVlLFxuICAgICAgICB9KSxcbiAgICBdLFxufSk7XG4iXSwKICAibWFwcGluZ3MiOiAiO0FBQStVLFNBQVMsb0JBQW9CO0FBQzVXLE9BQU8sYUFBYTtBQUVwQixJQUFPLHNCQUFRLGFBQWE7QUFBQSxFQUN4QixTQUFTO0FBQUEsSUFDTCxRQUFRO0FBQUEsTUFDSixPQUFPLENBQUMseUJBQXlCLHFCQUFxQjtBQUFBLE1BQ3RELFNBQVM7QUFBQSxJQUNiLENBQUM7QUFBQSxFQUNMO0FBQ0osQ0FBQzsiLAogICJuYW1lcyI6IFtdCn0K