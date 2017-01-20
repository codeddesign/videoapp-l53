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

  calculateFillRate(fills, requests) {
    // Fill Rate: fills/requests
    let fillRate = 0

    if (requests !== 0) {
      fillRate = ((fills / requests) * 100)
    }

    return fillRate.toFixed(2) + '%'
  },

  calculateEcpm(impressions, revenue, format = true) {
    let ecpm = revenue / (impressions / 1000)

    if (isNaN(ecpm)) {
      ecpm = 0
    }

    if (format) {
      return accounting.formatMoney(ecpm)
    } else {
      return ecpm
    }
  },

  calculateErrorRate(requests, adErrors) {
    // adErrors / requests
    let errorRate = 0

    if (requests !== 0) {
      errorRate = ((adErrors / requests) * 100)
    }

    return errorRate.toFixed(2) + '%'
  },

  calculateUseRate(impressions, fills) {
    let useRate = 0

    if (fills !== 0) {
      useRate = ((impressions / fills) * 100)
    }

    return useRate.toFixed(2) + '%'
  },

  calculateTagDisplayPercent(impressions, totalImpressions) {
    let tagDisplay = 0

    if (totalImpressions !== 0) {
      tagDisplay = ((impressions / totalImpressions) * 100)
    }

    return tagDisplay.toFixed(2) + '%'
  }
}
