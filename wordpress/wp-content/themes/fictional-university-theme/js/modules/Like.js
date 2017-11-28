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

    if('yes' === $likeBox.attr('data-exists')) {
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
      likeId => {
        const
          likeCount = parseInt($likeBox.find('.like-count').text(), 10)

        $likeBox.attr('data-exists', 'yes')
        $likeBox.attr('data-like-id', likeId)
        $likeBox.find('.like-count').text(likeCount + 1)
      },
      // failure
      e => {
        console.error(e)
      }
    )
  }

  deleteLike($likeBox) {
    $.ajax({
      'url': `${UNIVERSITY_DATA.rootUrl}/wp-json/university/v1/like`,
      'type': 'DELETE',
      'data': {
        'likeId': $likeBox.attr('data-like-id')
      },
      'beforeSend': xhr => {
        xhr.setRequestHeader('X-WP-Nonce', UNIVERSITY_DATA.nonce)
      }
    })
    .then(
      // success
      () => {
        const
          likeCount = parseInt($likeBox.find('.like-count').text(), 10)

        $likeBox.attr('data-exists', 'no')
        $likeBox.attr('data-like-id', '')
        $likeBox.find('.like-count').text(likeCount - 1)
      },
      // failure
      e => {
        console.error(e)
      }
    )
  }
}

export default Like
