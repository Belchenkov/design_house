import Vue from 'vue';
import * as VueGoogleMaps from 'vue2-google-maps-withscopedautocomp';

Vue.use(VueGoogleMaps, {
  load: {
    key: 'AIzaSyD_jwZZXKtOkt9biAnShp4HOPI6XgHCEHY',
    libraries: 'places'
  }
});
