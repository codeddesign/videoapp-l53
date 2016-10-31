import accounting from 'accounting'

export default {
  calculateRevenue(impressions, format = true) {
    let revenue = (4 * impressions) / 1000

    if (format) {
      return accounting.formatMoney(revenue)
    } else {
      return revenue
    }
  },

  calculateFillRate(impressions, requests) {
    // Fill Rate: impressions/requests
    if (requests === 0) {
      return 0
    }

    return ((impressions / requests) * 100).toFixed(2)
  },

  calculateEcpm(impressions, revenue) {
    let ecpm = revenue / (impressions / 1000)

    if (isNaN(ecpm)) {
      ecpm = 0
    }

    return accounting.formatMoney(ecpm)
  },

  calculateErrorRate(impressions, adErrors) {
    // adErrors / impressions
    if (impressions === 0) {
      return 0
    }

    return ((adErrors / impressions) * 100).toFixed(2) + '%'
  },

  calculateUseRate(impressions, fills) {
    if (fills === 0) {
      return 0
    }

    return ((impressions / fills) * 100).toFixed(2) + '%'
  }
}
