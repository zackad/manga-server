import { Controller } from '@hotwired/stimulus'
import { throttle } from 'lodash'

/* stimulusFetch: 'lazy' */
export default class extends Controller {
  prevScrollPosition = 0
  navbarHeight = 0

  connect() {
    this.navbarHeight = this.element.clientHeight
    addEventListener('scroll', throttle(this.scrollListener.bind(this), 1000))
  }

  scrollListener() {
    let currentScrollPos = window.scrollY
    if (currentScrollPos < this.navbarHeight) {
      return
    }

    if (this.prevScrollPosition > currentScrollPos) {
      this.element.classList.add('top-0')
      this.element.classList.remove('-top-12')
    } else {
      this.element.classList.add('-top-12')
      this.element.classList.remove('top-0')
    }
    this.prevScrollPosition = currentScrollPos
  }
}
