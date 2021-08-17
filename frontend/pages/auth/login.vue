<template>
  <section class="authentication">
    <div class="auth-body">
      <h1 class="text-uppercase fw-500 mb-4 text-center font-22">
        Login
      </h1>
      <form class="auth-form" @submit.prevent="submit">
        <alert-error
          class="text-center"
          :form="form"
          v-if="form.errors.has('verification')"
        >
          {{ form.errors.get('verification') }}
          <nuxt-link :to="{ name: 'verification.resend' }">
            Resend verification email
          </nuxt-link>
        </alert-error>
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
        <div class="mt-4 mb-4 clearfix">
          <a class="forgot-pass color-blue font-14 fw-400" href="#"> Forgot password? </a>
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
            Login
          </button>
        </div>
        <p class="font-14 fw-400 text-center mt-4">
          Don't have an account yet?
          <nuxt-link :to="{ name: 'register' }" class="color-blue"> Create an account</nuxt-link>
        </p>
      </form>
    </div>
  </section>
</template>

<script>
export default {
  name: "login",
  data() {
    return {
      form: this.$vform({
        email: '',
        password: '',
      })
    };
  },
  methods: {
    async submit() {
      try {
        const res = await this.$auth.loginWith('local', {
          data: this.form,
        });
        console.log(res);
        this.form.reset();
      } catch (err) {
        console.error(err);
        this.form.errors.set(err.response.data.errors);
      }
    }
  }
}
</script>

<style scoped>

</style>
