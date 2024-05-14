<script>
export default {
    name: "Header.vue"
}
</script>

<template>
    <div class="header">
        <div class="fixed-header"></div>
    </div>
</template>

<style scoped>
    .header {
        height: 70px;
    }
    .fixed-header{
        height: 70px;
        position: fixed;
        width: 100%;
        z-index: 20;
        background-color: #fff;
        box-shadow: 0 1px 0 0 rgba(17,17,17,.09);
    }
</style>