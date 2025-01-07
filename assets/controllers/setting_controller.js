import { Controller } from '@hotwired/stimulus'

/* stimulusFetch: 'lazy' */
export default class extends Controller {
  static targets = ['form', 'inputWidth', 'alert']

  connect() {
    let { width } = this.getInitialValue()
    this.inputWidthTarget.value = width
    this.formTarget.addEventListener('submit', this.handleSubmit.bind(this))
  }

  close() {
    this.alertTarget.classList.add('hidden')
  }

  open() {
    this.alertTarget.classList.remove('hidden')
    setTimeout(() => {
      this.close()
    }, 3000)
  }

  handleSubmit(event) {
    event.preventDefault()

    // Pass setting value into event detail and let app_controller.js handle it
    let eventDetail = {
      width: parseInt(event.target['max-image-width'].value),
    }
    this.dispatch('saved', {
      detail: eventDetail,
    })
    this.open()
  }

  getInitialValue() {
    const savedWidth = localStorage.getItem('imageContainerMaxWidth')
    return {
      width: savedWidth,
    }
  }
}
