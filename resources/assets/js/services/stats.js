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
    let fillRate = 0

    if (requests !== 0) {
      fillRate = ((impressions / requests) * 100)
    }

    return fillRate.toFixed(2) + '%'
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
    let errorRate =  0
    
    if (impressions !== 0) {
      errorRate = ((adErrors / impressions) * 100) 
    }

    return errorRate.toFixed(2) + '%'
  },

  calculateUseRate(impressions, fills) {
    let useRate = 0

    if (fills !== 0) {
      useRate = ((impressions / fills) * 100)
    }

    return useRate.toFixed(2) + '%'
  }
}
