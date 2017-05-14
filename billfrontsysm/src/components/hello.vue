<template>
  <div class="hello">
    <h1>{{ msg }}</h1>
    <h2>Essential Links</h2>
    <ul>
      <li><a href="httpss://vuejs.org" target="_blank">Core Docs</a></li>
      <li><a href="httpss://forum.vuejs.org" target="_blank">Forum</a></li>
      <li><a href="httpss://gitter.im/vuejs/vue" target="_blank">Gitter Chat</a></li>
      <li><a href="httpss://twitter.com/vuejs" target="_blank">Twitter</a></li>
      <br>
      <li><a href="https://vuejs-templates.github.io/webpack/" target="_blank">Docs for This Template</a></li>
    </ul>
    <h2>Ecosystem</h2>
    <ul>
      <li><a href="https://router.vuejs.org/" target="_blank">vue-router</a></li>
      <li><a href="https://vuex.vuejs.org/" target="_blank">vuex</a></li>
      <li><a href="https://vue-loader.vuejs.org/" target="_blank">vue-loader</a></li>
      <li><a href="httpss://github.com/vuejs/awesome-vue" target="_blank">awesome-vue</a></li>
    </ul>
  </div>
</template>
<script>
import api from '../api'
export default {
  name: 'hello',
  data() {
    return {
      msg: 'Welcomse to Your Vue.js App'
    }
  },
  mounted: function() {
    this.$nextTick(() => {
      // this.text();
    })
  },
  methods: {
    // async text() {
    //   const data = await api.get('');
    //   console.log(data);
    // }
  }
}
</script>
<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>
h1,
h2 {
  font-weight: normal;
}

ul {
  list-style-type: none;
  padding: 0;
}

li {
  display: inline-block;
  margin: 0 10px;
}

a {
  color: #42b983;
}
</style>
