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

  calculateFillRate(fills, requests, format = true) {
    // Fill Rate: fills/requests
    let fillRate = 0

    if (requests !== 0) {
      fillRate = ((fills / requests) * 100)
    }

    if (format) {
      return displayPercentage(fillRate)
    } else {
      return fillRate
    }
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

  calculateErrorRate(requests, errors, format = true) {
    // errors / requests
    let errorRate = 0

    if (requests !== 0) {
      errorRate = ((errors / requests) * 100)
    }

    if (format) {
      return displayPercentage(errorRate)
    } else {
      return errorRate
    }
  },

  calculateCompletionRate(impressions, completions, format = true) {
    // completions / impressions

    let completionRate = 0

    if (impressions !== 0) {
      completionRate = ((completions / impressions) * 100)
    }

    if (format) {
      return displayPercentage(completionRate)
    } else {
      return completionRate
    }
  },

  calculateUseRate(impressions, fills, format = true) {
    let useRate = 0

    if (fills !== 0) {
      useRate = ((impressions / fills) * 100)
    }

    if (format) {
      return displayPercentage(useRate)
    } else {
      return useRate
    }
  },

  calculateTagDisplayPercent(impressions, totalImpressions) {
    let tagDisplay = 0

    if (totalImpressions !== 0) {
      tagDisplay = ((impressions / totalImpressions) * 100)
    }

    return displayPercentage(tagDisplay)
  }
}
