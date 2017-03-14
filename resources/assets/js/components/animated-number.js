import Vue from 'vue'
import numeral from 'numeral'
import accounting from 'accounting'

Vue.component('animated-number', {
  render: function (createElement) {
    return createElement(
      'div',
      this.present(this.displayNumber)
    )
  },

  props: {
    'number': {
      default: 0
    },
    'type': {
      default: 'number'
    }
  },

  data: () => {
    return {
      displayNumber: 0,
      interval: false,
      ajaxDelay: 1500,
      increment: 0
    }
  },

  ready: () => {
    this.displayNumber = this.number ? this.number : 0
  },

  methods: {
    present(number) {
      if (this.type === 'money') {
        return this.presentRevenue(number)
      }

      return this.presentNumber(number)
    },

    presentNumber(number) {
      return numeral(number).format('0,0')
    },

    presentRevenue(number) {
      return accounting.formatMoney(number)
    }
  },

  watch: {
    number: function () {
      clearInterval(this.interval)

      // Don't animate when decreasing.
      if (this.number < this.displayNumber) {
        this.displayNumber = this.number
        return
      }

      this.increment = (this.number - this.displayNumber) / 100

      this.interval = window.setInterval(function() {
        if (+(this.number.toFixed(2)) > +(this.displayNumber.toFixed(2))) {
          this.displayNumber += this.increment
        } else {
          clearInterval(this.interval)
        }
      }.bind(this), this.ajaxDelay / 100)
    }
  }
})
