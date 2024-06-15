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
            centrifugo_token: null
        };
    },

    async mounted() {
        await this.getCentrifugoToken()
        await this.sse()
        this.textInterval = setInterval(this.updateInterval, 1000);
    },

    methods: {
        updateInterval() {
            this.$refs.convertPreloader.innerHTML = 'Конвертируем' + '.'.repeat(this.intervalValue);

            if (this.intervalValue >= 3) {
                this.intervalValue = 0;
            }

            this.intervalValue++;
        },

        async sse() {
            const url = new URL(window.location.origin + '/connection/uni_sse');

            let subs = {};
            subs[this.$route.params.uuid] = {
                recover: true,
            }

            url.searchParams.append("cf_connect", JSON.stringify({
                "token": this.centrifugo_token,
                "subs": subs
            }));

            const eventSource = new EventSource(url);

            eventSource.onmessage = (e) => {

                const data = JSON.parse(e.data);

                if (data.connect
                    && data.connect.subs[this.$route.params.uuid]
                    && data.connect.subs[this.$route.params.uuid].publications?.length
                ) {
                    this.files = data.connect.subs[this.$route.params.uuid].publications[0].data.files;
                    eventSource.close();
                    clearInterval(this.textInterval)
                    return;
                }

                if (data.pub?.data) {
                    this.files = data.pub?.data.files;
                    eventSource.close();
                    clearInterval(this.textInterval)
                }
            };
        },

        async getCentrifugoToken() {
            let response = null;

            try {
                response = await axios.get(`/api/v1/centrifugo/token/anonymous`)
            } catch (e) {
                alert(e.message);
                this.$router.push({name: 'home.page'})
                return;
            }

            if (!response.data?.success) {
                return;
            }

            this.centrifugo_token = response.data.data.token
        },
    },
};
</script>