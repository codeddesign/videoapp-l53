import Echo from 'laravel-echo'

export default {

  bootstrap(host, jwt) {
    if (typeof io !== 'undefined') {
      window.Echo = new Echo({
        broadcaster: 'socket.io',
        host: host,
        auth: {
          headers: {
            'Authorization': 'Bearer ' + jwt
          }
        }
      })
    }
  },

  connection() {
    let echo = window.Echo
    if (typeof echo !== 'undefined') {
      return echo
    } else {
      return null
    }
  }
}
