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
      this.deleteLike($likeBox)
    }
    else{
      this.createLike($likeBox)
    }
  }

  createLike($likeBox) {
    $.ajax({
      'url': `${UNIVERSITY_DATA.rootUrl}/wp-json/university/v1/like`,
      'type': 'POST',
      'data': {
        'professorId': $likeBox.data('professor-id')
      },
      'beforeSend': xhr => {
        xhr.setRequestHeader('X-WP-Nonce', UNIVERSITY_DATA.nonce)
      }
    })
    .then(
      // success
      () => {
      },
      // failure
      e => {
        console.error(e)
      }
    )
  }

  deleteLike() {
    $.ajax({
      'url': `${UNIVERSITY_DATA.rootUrl}/wp-json/university/v1/like`,
      'type': 'DELETE',
      'beforeSend': xhr => {
        xhr.setRequestHeader('X-WP-Nonce', UNIVERSITY_DATA.nonce)
      }
    })
    .then(
      // success
      () => {
      },
      // failure
      e => {
        console.error(e)
      }
    )
  }
}

export default Like
