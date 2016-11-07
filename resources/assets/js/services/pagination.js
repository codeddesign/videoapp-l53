import _ from 'lodash'

export default class Pagination {

  constructor(options, data) {
    this.page = 1
    this.options = options
    this.data = []
  }

  totalPages() {
    let pages = Math.ceil(this.data.length / this.options.perPage)

    if (pages === 0) {
      pages = 1
    }

    return pages
  }

  currentPage() {
    // The current page should be reset if
    // it's larger than the total number
    // of pages (e.g.: perPage changes)

    if(this.page > this.totalPages()) {
      this.page = this.totalPages()
    }

    return this.page
  }

  nextPage() {
    if (this.page === this.totalPages()) {
      return
    }

    this.page++
  }

  previousPage() {
    if (this.page === 1) {
      return
    }

    this.page--
  }

  getData() {
    return _.chunk(this.data, this.options.perPage)[this.page - 1]
  }
}
