export default {
  mode: 'universal',
  // Global page headers: https://go.nuxtjs.dev/config-head
  head: {
    title: 'Design House',
    htmlAttrs: {
      lang: 'en'
    },
    meta: [
      { charset: 'utf-8' },
      { name: 'viewport', content: 'width=device-width, initial-scale=1' },
      { hid: 'description', name: 'description', content: '' },
      { name: 'format-detection', content: 'telephone=no' }
    ],
    link: [
      { rel: 'icon', type: 'image/x-icon', href: '/favicon.ico' }
    ],
    script: [
      { src: "https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.slim.min.js" },
      { src: "https://cdnjs.cloudflare.com/ajax/libs/bootstrap/4.6.0/js/bootstrap.bundle.min.js" },
      { src: "https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/js/all.min.js" },
    ]
  },

  // Global CSS: https://go.nuxtjs.dev/config-css
  css: [
    '@/assets/scss/main.scss'
  ],

  // Plugins to run before rendering page: https://go.nuxtjs.dev/config-plugins
  plugins: [
  ],

  // Auto import components: https://go.nuxtjs.dev/config-components
  components: true,

  // Modules for dev and build (recommended): https://go.nuxtjs.dev/config-modules
  buildModules: [
    '@nuxtjs/eslint-module',
    '@nuxtjs/router',
    //'@nuxtjs/stylelint-module',
  ],

  // Modules: https://go.nuxtjs.dev/config-modules
  modules: [
    'bootstrap-vue/nuxt',
    '@nuxtjs/axios',
    '@nuxtjs/dotenv',
    '@nuxtjs/axios',
    '@nuxtjs/auth-next',
  ],
  // https://auth.nuxtjs.org/schemes/local
  auth: {
    strategies: {
      local: {
        token: {
          property: 'token',
          global: true,
          // required: true,
          // type: 'Bearer'
        },
        user: {
          property: 'user',
          // autoFetch: true
        },
        endpoints: {
          login: { url: '/login', method: 'post', propertyName: 'token' },
          logout: { url: '/logout', method: 'post' },
          user: { url: '/me', method: 'get', propertyName: 'data' }
        }
      }
    }
  },

  // Axios module configuration: https://go.nuxtjs.dev/config-axios
  axios: {
    baseUrl: process.env.API_URL
  },

  // Build Configuration: https://go.nuxtjs.dev/config-build
  build: {
  }
}
