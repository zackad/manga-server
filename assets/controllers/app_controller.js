import { Controller } from '@hotwired/stimulus'
import lozad from 'lozad'

/* stimulusFetch: 'lazy' */
export default class extends Controller {
  static targets = ['image', 'imageContainer', 'entryContainer']
  isReaderModeActive = false

  connect() {
    const observer = lozad('.lozad', {
      loaded: (el) => el.classList.remove('min-h-screen'),
    })
    observer.observe()
    addEventListener('keydown', this.keyPressListener.bind(this))
  }

  disconnect() {
    removeEventListener('keydown', this.keyPressListener)
  }

  keyPressListener(event) {
    switch (event.keyCode) {
      case 13:
        this.activateReader()
        break
      case 220:
        this.deactivateReader()
        break
    }
  }

  activateReader() {
    if (this.imageTargets.length === 0) {
      return
    }

    this.imageContainerTarget.classList.remove('hidden')
    this.entryContainerTarget.classList.add('hidden')
    this.isReaderModeActive = true
  }

  deactivateReader() {
    if (this.imageTargets.length === 0) {
      return
    }

    this.entryContainerTarget.classList.remove('hidden')
    this.imageContainerTarget.classList.add('hidden')
    this.isReaderModeActive = false
  }

  toggleReader() {
    this.isReaderModeActive && this.imageTargets.length > 0 ? this.deactivateReader() : this.activateReader()
  }
}
