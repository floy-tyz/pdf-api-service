<template>
    <div class="home-page">

        <h1> Your files </h1>

        <div class="form">

            <label class="center" ref="convertPreloader" v-show="!files.length">
                Конвертируем...
            </label>

            <div class="files-table center" v-show="files.length">
                <div v-for="file in files">
                    <a :href="file.href" target="_blank">{{ file.name }}</a>
                </div>
            </div>

        </div>
    </div>
</template>

<script>

import axios from "axios";

export default {
    name: "Conversion",

    data() {
        return {
            textInterval: null,
            poolingInterval: null,
            intervalValue: 0,
            files: [],
        };
    },

    mounted() {
        this.pooling()
        this.textInterval = setInterval(this.updateInterval, 1000);
        this.poolingInterval = setInterval(this.pooling, 3000);
    },

    methods: {
        updateInterval() {
            this.$refs.convertPreloader.innerHTML = 'Конвертируем' + '.'.repeat(this.intervalValue);

            if (this.intervalValue >= 3) {
                this.intervalValue = 0;
            }

            this.intervalValue++;
        },

        // todo change to sse
        async pooling() {

            let response = null;

            try {
                response = await axios.get(`/api/v1/process/${this.$route.params.uuid}/files`)
            } catch (e) {
                alert(e.message);
                this.$router.push({name: 'home.page'})
                return;
            }

            if (!response.data?.success) {
                return;
            }

            clearInterval(this.textInterval);
            clearInterval(this.poolingInterval);

            this.files = response.data.files;
        },
    },
};
</script>