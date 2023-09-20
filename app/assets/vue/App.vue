<template>
    <div class="app">

        <Header ref="appHeader"></Header>

        <div class="content">

            <router-view></router-view>

        </div>

    </div>
</template>

<script>

import Header from './includes/Header.vue'

export default {
    name: "App.vue",
    components: {Header}
}
</script>

<style scoped>

.content {
    flex: 1 1 auto;
    width: 100%;
    max-width: 1150px;
    margin: 0 auto;
    padding-top: 30px;
}

</style>