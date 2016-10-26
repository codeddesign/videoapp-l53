import Cookies from 'js-cookie'
import ls from './ls'

export default {
  authenticate(redirect = '/login') {
    let jwt = ls.get('jwt')

    if(jwt === null) {
      jwt = Cookies.get('jwt_token')

      if(typeof jwt === 'undefined') {
        window.location.href = redirect
      } else {
        ls.set('jwt', jwt)
      }
    }

    return jwt
  }
}
