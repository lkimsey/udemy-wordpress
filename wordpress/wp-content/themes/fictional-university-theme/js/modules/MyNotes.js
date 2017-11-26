import $ from 'jquery'

class MyNotes {
  // 1. Initialize
  constructor() {
    this.$deleteButton = $('.delete-note')
    this.$editButton = $('.edit-note')

    this.events()
  }

  // 2. Events
  events() {
    this.$deleteButton.on('click', this.deleteNote.bind(this))
    this.$editButton.on('click', this.editNote.bind(this))
  }

  // 3. Methods
  editNote(e) {
    const
      $noteItem = $(e.target).parents('li')

    if('editable' === $noteItem.data('state')) {
      this.makeNoteReadonly($noteItem)
    }
    else {
      this.makeNoteEditable($noteItem)
    }
  }

  makeNoteEditable($noteItem) {
    $noteItem
      .data('state', 'editable')
      .find('.note-title-field, .note-body-field')
        .removeAttr('readonly')
        .addClass('note-active-field')
        .end()
      .find('.update-note')
        .addClass('update-note--visible')
        .end()
      .find('.edit-note')
        .html('<i class="fa fa-times" aria-hidden="true"></i> Cancel')
  }

  makeNoteReadonly($noteItem) {
    $noteItem
      .data('state', '')
      .find('.note-title-field, .note-body-field')
        .attr('readonly', 'readonly')
        .removeClass('note-active-field')
        .end()
      .find('.update-note')
        .removeClass('update-note--visible')
        .end()
      .find('.edit-note')
        .html('<i class="fa fa-pencil" aria-hidden="true"></i> Edit')
  }

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
