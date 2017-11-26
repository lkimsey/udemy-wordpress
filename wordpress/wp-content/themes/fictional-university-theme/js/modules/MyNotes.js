import $ from 'jquery'

class MyNotes {
  // 1. Initialize
  constructor() {
    this.events()
  }

  // 2. Events
  events() {
    $('#myNotes')
      .on('click', '.delete-note', this.deleteNote.bind(this))
      .on('click', '.edit-note', this.editNote.bind(this))
      .on('click', '.update-note', this.updateNote.bind(this))

    $('.submit-note').on('click', this.createNote.bind(this))
  }

  // 3. Methods
  createNote(e) {
    const
      note = {
        'title': $('.new-note-title').val(),
        'content': $('.new-note-body').val()
      }

    $.ajax({
      'url': `${UNIVERSITY_DATA.rootUrl}/wp-json/wp/v2/note/`,
      'type': 'POST',
      'data': note,
      'beforeSend': xhr => {
        xhr.setRequestHeader('X-WP-Nonce', UNIVERSITY_DATA.nonce)
      }
    })
    .then(
      // success
      newNote => {
        $('.new-note-title, .new-note-body').val('')

        $(`
          <li data-id="${newNote.id}">
            <input class="note-title-field" value="${newNote.title.raw}" readonly />
            <span class="edit-note"><i class="fa fa-pencil" aria-hidden="true"></i> Edit</span>
            <span class="delete-note"><i class="fa fa-trash-o" aria-hidden="true"></i> Delete</span>
            <textarea class="note-body-field" readonly>${newNote.content.raw}</textarea>
            <span class="update-note btn btn--blue btn--small"><i class="fa fa-arrow-right" aria-hidden="true"></i> Save</span>
          </li>
        `)
          .hide()
          .prependTo('#myNotes')
          .slideDown()
      },
      // failure
      e => {
        if('NOTE_LIMIT_REACHED' === e.responseText) {
          $('.note-limit-message').addClass('active')
        }
        else {
          console.error(e)
        }
      }
    )
  }

  updateNote(e) {
    const
      $noteItem = $(e.target).parents('li'),
      note = {
        'title': $noteItem.find('.note-title-field').val(),
        'content': $noteItem.find('.note-body-field').val()
      }

    $.ajax({
      'url': `${UNIVERSITY_DATA.rootUrl}/wp-json/wp/v2/note/${$noteItem.data('id')}`,
      'type': 'POST',
      'data': note,
      'beforeSend': xhr => {
        xhr.setRequestHeader('X-WP-Nonce', UNIVERSITY_DATA.nonce)
      }
    })
    .then(
      // success
      () => {
        this.makeNoteReadonly($noteItem)
      },
      // failure
      e => {
        console.error(e)
      }
    )
  }

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

        if(5 > response.userNoteCount) {
          $('.note-limit-message').removeClass('active')
        }
      },
      // failure
      e => {
        console.error(e)
      }
    )
  }
}

export default MyNotes
