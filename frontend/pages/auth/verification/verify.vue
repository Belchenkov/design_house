<template>
  <section class="authentication">
    <div class="auth-body">
      <h1 class="text-uppercase fw-500 mb-4 text-center font-22">
        Email Verification
      </h1>
      <form class="auth-form">
        <div class="form-group" v-if="success">
          <div class="alert alert-success">
            {{ status }}
          </div>
          <nuxt-link to="/login">Proceed to Login</nuxt-link>
        </div>
        <div class="form-group" v-else>
          <div class="alert alert-danger">
            {{ status }}
          </div>
        </div>
      </form>
    </div>
  </section>
</template>

<script>
export default {
  name: "verify",
  async asyncData({ params, query, app }) {
    const q = await Object.keys(query)
      .map(k => `${k}=${query[k]}`)
      .join('&');
    try {
      const { data } = await app.$axios.post(
        `/verification/verify/${params.id}?${q}`
      );
      console.log(data);
      return { success: true, status: data.message };
    } catch (err) {
      return { success: false, status: err.response.data.errors.message };
    }
  }
}
</script>

<style scoped>

</style>
