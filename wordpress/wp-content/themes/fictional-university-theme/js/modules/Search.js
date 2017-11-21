import $ from 'jquery'

class Search {
  // 1. Describe and create/initiate our object.
  constructor() {
    this.$openButton = $('.js-search-trigger')
    this.$closeButton = $('.search-overlay__close')
    this.$searchOverlay = $('.search-overlay')
    this.$searchField = $('#searchTerm')
    this.$results = $('.search-overlay__results')

    this.isOverlayOpen = false
    this.isSpinnerVisible = false

    this.typingTimer = null

    this.events()
  }

  // 2. Events
  events() {
    this.$openButton.on('click', this.openOverlay.bind(this))
    this.$closeButton.on('click', this.closeOverlay.bind(this))

    $(document).on('keydown', this.keyPressDispatcher.bind(this))

    this.$searchField.on('input', this.typingLogic.bind(this))
  }

  // 3. Methods (functions, actions...)
  typingLogic() {
    clearTimeout(this.typingTimer)

    if(1 > this.$searchField.val().length) {
      this.$results.html('')

      this.isSpinnerVisible = false

      return
    }

    if(!this.isSpinnerVisible) {
      this.$results.html('<div class="spinner-loader"></div>')

      this.isSpinnerVisible = true
    }

    this.typingTimer = setTimeout(this.getResults.bind(this), 500)
  }

  getResults() {
    $.getJSON(`${UNIVERSITY_DATA.rootUrl}/wp-json/wp/v2/posts/?search=${this.$searchField.val()}`, posts => {
      const
        generalList = 0 === posts.length ? '<p>No general information matching that search</p>' : `
          <ul class="link-list min-list">
            ${posts.map(item => `<li><a href="${item.link}">${item.title.rendered}</a></li>`).join('')}
          </ul>
        `

      this.$results.html(`
      <h2 class="search-overlay__section-title">General Information</h2>
      ${generalList}
      `)

      this.isSpinnerVisible = false
    })
  }

  keyPressDispatcher(e) {
    if(83 === e.keyCode && !this.isOverlayOpen && !$('input, textarea, select').is(':focus')) {
      this.openOverlay()
    }
    else if(27 === e.keyCode && this.isOverlayOpen) {
      this.closeOverlay()
    }
  }

  openOverlay() {
    this.$searchOverlay.addClass('search-overlay--active')

    $('body').addClass('body-no-scroll')
    this.isOverlayOpen = true
  }

  closeOverlay() {
    this.$searchOverlay.removeClass('search-overlay--active')

    $('body').removeClass('body-no-scroll')
    this.isOverlayOpen = false
  }
}

export default Search
