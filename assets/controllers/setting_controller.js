import { Controller } from '@hotwired/stimulus'

/* stimulusFetch: 'lazy' */
export default class extends Controller {
  static targets = ['modalContainer', 'form', 'inputWidth']

  connect() {
    let { width } = this.getInitialValue()
    this.inputWidthTarget.value = width
    this.formTarget.addEventListener('submit', this.handleSubmit.bind(this))
  }

  close() {
    this.modalContainerTarget.classList.add('hidden')
  }

  open() {
    this.modalContainerTarget.classList.remove('hidden')
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
    this.close()
  }

  getInitialValue() {
    const savedWidth = localStorage.getItem('imageContainerMaxWidth')
    return {
      width: savedWidth,
    }
  }
}
