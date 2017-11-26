import $ from 'jquery'

class Like {
  // 1. Initialize
  constructor() {
    this.events()
  }

  // 2. Events
  events() {
    $('.like-box').on('click', this.clickDispatcher.bind(this))
  }

  // 3. Methods
  clickDispatcher(e) {
    const
      $likeBox = $(e.target).closest('.like-box')

    if('yes' === $likeBox.data('exists')) {
      this.deleteLike()
    }
    else{
      this.createLike()
    }
  }

  createLike() {
  }

  deleteLike() {
  }
}

export default Like
