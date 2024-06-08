<template>
    <div class="home-page">

        <h1> Easypdf </h1>

        <div class="form">
            <router-link to="image-to-pdf">Изображение в пфд</router-link>
            <router-link to="office-to-pdf">Офис в пфд</router-link>

        </div>
    </div>
</template>

<script>


export default {
    name: "Home",
};
</script>