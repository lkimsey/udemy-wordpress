import $ from 'jquery'

class Search {
  // 1. Describe and create/initiate our object.
  constructor() {
    // NOTE: add static HTML to DOM before initializing jQuery objects for them
    this.addSeatchHTML()

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
    $.getJSON(`${UNIVERSITY_DATA.rootUrl}/wp-json/university/v1/search/?term=${this.$searchField.val()}`)
      .then(
        // Success
        results => {
          const
            generalList = 0 === results.generalInfo.length ? '<p>No general information match that search.</p>' : `
              <ul class="link-list min-list">
                ${results.generalInfo.map(item => `<li><a href="${item.permalink}">${item.title}</a>${ 'post' === item.postType ? ` by ${item.authorName}` : '' }</li>`).join('')}
              </ul>
            `,

            programList = 0 === results.programs.length ? `<p>No programs match that search. <a href="${UNIVERSITY_DATA.rootUrl}/programs">View all programs.</a></p>` : `
              <ul class="link-list min-list">
                ${results.programs.map(item => `<li><a href="${item.permalink}">${item.title}</a></li>`).join('')}
              </ul>
            `,

            campusList = 0 === results.campuses.length ? `<p>No campuses match that search. <a href="${UNIVERSITY_DATA.rootUrl}/campuses">View all campuses.</a></p></p>` : `
              <ul class="link-list min-list">
                ${results.campuses.map(item => `<li><a href="${item.permalink}">${item.title}</a></li>`).join('')}
              </ul>
            `,

            professorList = 0 === results.professors.length ? '<p>No professors match that search.' : `
              <ul class="professor-cards">
                ${results.professors.map(item => `
                  <li class="professor-card__list-item">
                    <a class="professor-card" href="${item.permalink}">
                      <img class="professor-card__image" src="${item.image}" />
                      <span class="professor-card__name">${item.title}</span>
                    </a>
                  </li>
                `).join('')}
              </ul>
            `,

            eventList = 0 === results.events.length ? `<p>No events match that search. <a href="${UNIVERSITY_DATA.rootUrl}/events">View all events.</a></p></p>` :
              results.events.map(item => `
                <div class="event-summary">
                  <a class="event-summary__date t-center" href="${item.permalink}">
                    <span class="event-summary__month">${item.month}</span>
                    <span class="event-summary__day">${item.day}</span>
                  </a>
                  <div class="event-summary__content">
                    <h5 class="event-summary__title headline headline--tiny"><a href="${item.permalink}">${item.title}</a></h5>
                    <p>
                    ${item.description}
                    <a href="${item.permalink}" class="nu gray">Read more</a>
                    </p>
                  </div>
                </div>
              `).join('')

          this.$results.html(`
            <div class="row">
              <div class="one-third">
                <h2 class="search-overlay__section-title">General Information</h2>
                ${generalList}
              </div>
              <div class="one-third">
                <h2 class="search-overlay__section-title">Programs</h2>
                ${programList}

                <h2 class="search-overlay__section-title">Professors</h2>
                ${professorList}
              </div>
              <div class="one-third">
                <h2 class="search-overlay__section-title">Campuses</h2>
                ${campusList}

                <h2 class="search-overlay__section-title">Events</h2>
                ${eventList}
              </div>
            </div>
          `)

          this.isSpinnerVisible = false
        },

        // Failure
        () => {
          this.$results.html('<p>Unexpected error, please try again</p>')
        }
      )
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

    this.$searchField.val('')
    $('body').addClass('body-no-scroll')
    this.isOverlayOpen = true

    // Wait until overlay animation is complete (~300ms), and focus on the input
    setTimeout(() => this.$searchField.focus(), 301)

    return false;
  }

  closeOverlay() {
    this.$searchOverlay.removeClass('search-overlay--active')

    $('body').removeClass('body-no-scroll')
    this.isOverlayOpen = false
  }

  addSeatchHTML() {
    $('body').append(`
      <div class="search-overlay">
        <div class="search-overlay__top">
          <div class="container">
            <i class="fa fa-search search-overlay__icon" aria-hidden="true"></i>
            <input type="text" class="search-term" placeholder="What are you looking for?" id="searchTerm" />
            <i class="fa fa-window-close search-overlay__close" aria-hidden="true"></i>
          </div>
        </div>

        <div class="container">
          <div class="search-overlay__results">
          </div>
        </div>
      </div>
    `)
  }
}

export default Search
