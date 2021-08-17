<template>
  <section class="authentication">
    <div class="auth-body">
      <h1 class="text-uppercase fw-500 mb-4 text-center font-22">
        Register
      </h1>
      <form class="auth-form" @submit.prevent="submit">
        <div class="form-group">
          <input
            v-model.trim="form.name"
            type="text"
            name="name"
            class="form-control form-control-lg font-14 fw-300"
            :class="{ 'is-invalid': form.errors.has('name') }"
            placeholder="Full Name"
          />
          <has-error :form="form" field="name" />
        </div>
        <div class="form-group">
          <input
            v-model.trim="form.username"
            type="text"
            name="username"
            class="form-control form-control-lg font-14 fw-300"
            :class="{ 'is-invalid': form.errors.has('username') }"
            placeholder="Username"
          />
          <has-error :form="form" field="username" />
        </div>
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
            type="submit"
            class="btn btn-primary primary-bg-color font-16 fw-500 text-uppercase"
            :disabled="form.busy"
          >
            <span v-if="form.busy">
              <i class="fas fa-spinner fa-spin"></i>
            </span>
            Register
          </button>
        </div>
        <p class="font-14 fw-400 text-center mt-4">
          Already have an account?
          <a class="color-blue" href="#"> Login</a>
        </p>
      </form>
    </div>
  </section>
</template>

<script>
export default {
  name: "register",
  data() {
    return {
      form: this.$vform({
        username: '',
        name: '',
        email: '',
        password: '',
        password_confirmation: '',
      }),
    };
  },
  methods: {
    async submit() {
      try {
        const res = await this.form.post(`/register`);
        console.log(res);
      } catch (err) {
        console.error(err);
      }
    }
  }
}
</script>

<style scoped>

</style>
