<template>
  <div>
    <Navbar />
    <div class="wrapper">
      <nuxt />
    </div>
    <Footer />
  </div>
</template>

<script>
import Navbar from "./partials/Navbar";
import Footer from "./partials/Footer";

export default {
  name: "default",
  components: {
    Navbar,
    Footer,
  }
}
</script>

<style scoped>

</style>
