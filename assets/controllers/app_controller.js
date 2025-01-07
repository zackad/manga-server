import { Controller } from '@hotwired/stimulus'

/* stimulusFetch: 'lazy' */
export default class extends Controller {
  static targets = ['image', 'imageContainer', 'entryContainer']
  isReaderModeActive = false
  imageContainerMaxWidth = null

  connect() {
    // Add keyboard event listener
    addEventListener('keydown', this.keyPressListener.bind(this))
    // Listen to setting_controller.js event
    addEventListener('setting:saved', this.saveSettings.bind(this))

    // Set maximum image container width
    this.setMaxImageContainerWidth()
  }

  disconnect() {
    removeEventListener('keydown', this.keyPressListener)
  }

  saveSettings(event) {
    let width = event.detail.width ? event.detail.width : null
    localStorage.setItem('imageContainerMaxWidth', width)
    this.setMaxImageContainerWidth()
  }

  setMaxImageContainerWidth() {
    // do nothing if imageContainer missing
    if (!this.hasimageContainerTarget) {
      return
    }

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
