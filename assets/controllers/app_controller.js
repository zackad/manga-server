import { Controller } from '@hotwired/stimulus'
import lozad from 'lozad'

/* stimulusFetch: 'lazy' */
export default class extends Controller {
  static targets = ['image', 'imageContainer', 'entryContainer']
  isReaderModeActive = false
  imageContainerMaxWidth = null

  connect() {
    // Initialize image lazy load observer
    // FIXME: extract into separate controller
    const observer = lozad('.lozad', {
      loaded: (element) => element.addEventListener('load', this.lazyLoadListener.bind(this)),
    })
    observer.observe()

    // Add keyboard event listener
    addEventListener('keydown', this.keyPressListener.bind(this))
    // Listen to setting_controller.js event
    addEventListener('setting:saved', this.saveSettings.bind(this))

    // Set maximum image container width
    this.setMaxImageContainerWidth()
  }

  disconnect() {
    removeEventListener('keydown', this.keyPressListener)

    // Remove event listener from lazy loaded images
    const images = [...document.querySelectorAll('.lozad')]
    images.forEach((image) => image.removeEventListener('load', this.lazyLoadListener.bind(this)))
  }

  saveSettings(event) {
    let width = event.detail.width ? event.detail.width : null
    localStorage.setItem('imageContainerMaxWidth', width)
    this.setMaxImageContainerWidth()
  }

  setMaxImageContainerWidth() {
    this.imageContainerMaxWidth = localStorage.getItem('imageContainerMaxWidth')
    this.imageContainerTarget.style.maxWidth = isNaN(this.imageContainerMaxWidth)
      ? '100%'
      : `${this.imageContainerMaxWidth}px`
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

  lazyLoadListener(event) {
    const image = event.target
    image.classList.remove('min-h-screen')
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
