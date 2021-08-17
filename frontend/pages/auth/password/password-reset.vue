<template>
  <section class="authentication">
    <div class="auth-body">
      <h1 class="text-uppercase fw-500 mb-4 text-center font-22">
        Reset Password
      </h1>
      <form class="auth-form" @submit.prevent="submit">
        <alert-success :form="form" class="text-center">
          {{ status }}
          <p>
            <nuxt-link to="/login">Proceed to login</nuxt-link>
          </p>
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
        <div class="form-group">
          <input
            v-model.trim="form.password"
            type="password"
            name="password"
            class="form-control form-control-lg font-14 fw-300"
            :class="{ 'is-invalid': form.errors.has('password') }"
            placeholder="Password"
          />
          <has-error :form="form" field="password" />
        </div>
        <div class="form-group">
          <input
            v-model.trim="form.password_confirmation"
            type="password"
            name="password_confirmation"
            class="form-control form-control-lg font-14 fw-300"
            placeholder="Confirm Password"
            :class="{ 'is-invalid': form.errors.has('password_confirmation') }"
          />
          <has-error :form="form" field="password_confirmation" />
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
            Reset Password
          </button>
        </div>
      </form>
    </div>
  </section>
</template>

<script>
export default {
  name: "password-reset",
  data() {
    return {
      status: '',
      form: this.$vform({
        email: '',
        password: '',
        password_confirmation: '',
        token: ''
      })
    };
  },
  methods: {
    submit() {
      this.form
        .post('/password/reset')
        .then(res => {
          this.status = res.data.status;
          this.form.reset();
        })
        .catch(e => {
          console.log(e);
        });
    }
  },
  created() {
    this.form.email = this.$route.query.email;
    this.form.token = this.$route.params.token;
  }
}
</script>

<style scoped>

</style>
