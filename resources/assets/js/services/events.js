export default {
  isRequest(event) {
    return event.source === 'tag'
  },

  isFill(event) {
    return event.source === 'ad' && event.status === 0
  },

  isImpression(event) {
    return event.source === 'ad' && event.status === 3
  },

  isAdError(event) {
    return event.source === 'ad' && event.status >= 100
  },

  type(event) {
    if (this.isRequest(event)) {
      return 'request'
    } else if (this.isImpression(event)) {
      return 'impression'
    } else if (this.isFill(event)) {
      return 'fill'
    } else if (this.isAdError(event)) {
      return 'ad-error'
    }

    return null
  }
}
