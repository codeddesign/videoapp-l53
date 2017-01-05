export default {
  isRequest(event) {
    return event.source === 'campaign' && event.status === 200
  },

  isFill(event) {
    return event.source === 'tag' && event.status === 200
  },

  isTagError(event) {
    return event.source === 'tag' && event.status !== 200
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
    } else if (this.isTagError(event)) {
      return 'tag-error'
    } else if (this.isAdError(event)) {
      return 'ad-error'
    }

    return null
  }
}
