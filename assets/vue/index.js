import { createApp } from "vue";

import App from "./App.vue";
import router from "./core/router";

export default {
    id: "home",

    init() {
        this.handleFilter();
    },

    handleFilter() {
        if (!document.querySelector(`#app`)) {
            throw new Error('#app not found')
        }

        const app = createApp(App);

        app.use(router)

        app.mount("#app");
    },
};
