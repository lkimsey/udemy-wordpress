import $ from 'jquery'

class MyNotes {
  // 1. Initialize
  constructor() {
    this.$deleteButton = $('.delete-note')

    this.events()
  }

  // 2. Events
  events() {
    this.$deleteButton.on('click', this.deleteNote.bind(this))
  }

  // 3. Methods
  deleteNote(e) {
    const
      $noteItem = $(e.target).parents('li')

    $.ajax({
      'url': `${UNIVERSITY_DATA.rootUrl}/wp-json/wp/v2/note/${$noteItem.data('id')}`,
      'type': 'DELETE',
      'beforeSend': xhr => {
        xhr.setRequestHeader('X-WP-Nonce', UNIVERSITY_DATA.nonce)
      }
    })
    .then(
      // success
      response => {
        $noteItem.slideUp()
      },
      // failure
      e => {
        console.error(e)
      }
    )
  }
}

export default MyNotes
