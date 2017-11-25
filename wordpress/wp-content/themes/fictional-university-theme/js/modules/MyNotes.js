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
  deleteNote() {
  }
}

export default MyNotes
