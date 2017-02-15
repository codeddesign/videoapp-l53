import accounting from 'accounting'

function displayPercentage(number) {
  number = (number <= 100) ? number : 100

  return number.toFixed(2) + '%'
}

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

    return displayPercentage(fillRate)
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

  calculateErrorRate(requests, errors) {
    // errors / requests
    let errorRate = 0

    if (requests !== 0) {
      errorRate = ((errors / requests) * 100)
    }

    return displayPercentage(errorRate)
  },

  calculateUseRate(impressions, fills) {
    let useRate = 0

    if (fills !== 0) {
      useRate = ((impressions / fills) * 100)
    }

    return displayPercentage(useRate)
  },

  calculateTagDisplayPercent(impressions, totalImpressions) {
    let tagDisplay = 0

    if (totalImpressions !== 0) {
      tagDisplay = ((impressions / totalImpressions) * 100)
    }

    return displayPercentage(tagDisplay)
  }
}
