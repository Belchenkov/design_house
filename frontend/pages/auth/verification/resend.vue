<template>
  <section class="authentication">
    <div class="auth-body">
      <h1 class="text-uppercase fw-500 mb-4 text-center font-22">
        Resend Verification Email
      </h1>
      <form class="auth-form" @submit.prevent="submit">
        <alert-error
          class="text-center"
          v-if="form.errors.has('message')"
          :form="form"
        >
          {{ form.errors.get('message') }}
        </alert-error>
        <alert-success :form="form" class="text-center">
          We have resent the verification email
        </alert-success>
        <div class="form-group">
          <input
            v-model.trim="form.email"
            type="text"
            name="email"
            class="form-control form-control-lg font-14 fw-300"
            :class="{ 'is-invalid': form.errors.has('email') }"
            placeholder="Email"
          />
          <has-error :form="form" field="email" />
        </div>

        <div class="text-right">
          <button
            :disabled="form.busy"
            type="submit"
            class="btn btn-primary primary-bg-color font-16 fw-500 text-uppercase"
          >
            <span v-if="form.busy">
              <i class="fas fa-spinner fa-spin"></i>
            </span>
            Resend
          </button>
        </div>
      </form>
    </div>
  </section>
</template>

<script>
export default {
  name: "resend",
  data() {
    return {
      form: this.$vform({
        email: ''
      })
    };
  },
  methods: {
    async submit() {
      try {
        await this.form.post(`/verification/resend`);
        this.form.reset();
      } catch (err) {
        console.error(err);
      }
    }
  }
}
</script>

<style scoped>

</style>
