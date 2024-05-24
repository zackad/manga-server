import { Controller } from '@hotwired/stimulus'
import lozad from 'lozad'

/* stimulusFetch: 'lazy' */
export default class extends Controller {
  static targets = ['image', 'reload']

  connect() {
    // Initialize image lazy load observer
    const observer = lozad('.lozad', {
      loaded: (element) => {
        element.addEventListener('load', this.onLoad.bind(this))
        element.addEventListener('error', this.onError.bind(this))
      },
    })
    observer.observe()
  }

  disconnect() {
    // Remove event listener from lazy loaded images
    const images = [...document.querySelectorAll('.lozad')]
    images.forEach((image) => image.removeEventListener('load', this.onLoad.bind(this)))
  }

  reload() {
    const url = this.imageTarget.dataset.src
    fetch(url)
      .then((response) => {
        this.imageTarget.src = url
        this.imageTarget.classList.remove('hidden')
        this.reloadTarget.classList.add('hidden')
      })
      .catch(() => {})
  }

  onLoad(event) {
    const image = event.target
    image.classList.remove('min-h-screen')
  }

  onError(event) {
    event.target.closest('div').querySelector('div').classList.remove('hidden')
    event.target.classList.add('hidden')
  }
}
