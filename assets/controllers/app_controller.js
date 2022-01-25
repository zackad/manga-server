import { Controller } from '@hotwired/stimulus'
import lozad from 'lozad'

export default class extends Controller {
  static targets = ['image', 'entry']
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

    this.entryTargets.forEach(this.hideElement)
    this.imageTargets.forEach(this.showElement)
    this.isReaderModeActive = true
  }

  deactivateReader() {
    if (this.imageTargets.length === 0) {
      return
    }

    this.entryTargets.forEach(this.showElement)
    this.imageTargets.forEach(this.hideElement)
    this.isReaderModeActive = false
  }

  toggleReader() {
    this.isReaderModeActive && this.imageTargets.length > 0 ? this.deactivateReader() : this.activateReader()
  }

  hideElement(element) {
    element.classList.add('hidden')
  }

  showElement(element) {
    element.classList.remove('hidden')
  }
}
