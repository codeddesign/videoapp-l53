import Vue from 'vue'
import numeral from 'numeral'

Vue.component('animated-number', {
  render: function (createElement) {
    return createElement(
      'div',
      this.presentNumber(this.displayNumber)
    )
  },

  props: {
    'number': {
      default: 0
    }
  },

  data: () => {
    return {
      displayNumber: 0,
      interval: false
    }
  },

  ready: () => {
    this.displayNumber = this.number ? this.number : 0
  },

  methods: {
    presentNumber(number) {
      return numeral(number).format('0,0')
    }
  },

  watch: {
    number: function () {
      clearInterval(this.interval)

      if (this.number === this.displayNumber) {
        return
      }

      this.interval = window.setInterval(function() {
        if (this.displayNumber !== this.number) {
          var change = (this.number - this.displayNumber) / 10
          change = change >= 0 ? Math.ceil(change) : Math.floor(change)
          this.displayNumber = this.displayNumber + change
        }
      }.bind(this), 20)
    }
  }
})
