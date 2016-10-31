export default {
  isRequest(event) {
    return event.source === 'app' && event.status === 200
  },

  isImpression(event) {
    return event.source === 'ad' && event.status === 0
  },

  isFill(event) {
    return event.source === 'tag' && event.status === 0
  },

  isTagError(event) {
    return event.source === 'tag' && event.status !== 0
  },

  isAdError(event) {
    return event.source === 'ad' && event.status !== 0
  },

  type(event) {
    if (this.isRequest(event)) {
      return 'request'
    } else if (this.isImpression(event)) {
      return 'impression'
    } else if (this.isFill(event)) {
      return 'fill'
    } else if (this.isTagError(event)) {
      return 'tag-error'
    } else if (this.isAdError(event)) {
      return 'ad-error'
    }

    return null
  }
}
