import { Controller } from '@hotwired/stimulus'
import { Modal } from 'bootstrap'

export default class extends Controller {
    static targets = ['modal', 'buttonNew']

    connect() {
        this.bootstrapModal = new Modal(this.modalTarget)
        this.attachEvents()
    }

    open() {
        this.bootstrapModal.show()
    }

    close() {
        this.bootstrapModal.hide()
    }

    // Optional: Add listeners for modal events
    initialize() {
        this.handleShown = this.handleShown.bind(this)
        this.handleHidden = this.handleHidden.bind(this)
    }

    handleShown() {
        // Do something when modal is shown
    }

    handleHidden() {
        const form = this.modalTarget.querySelector('form')
        if (form) form.reset()
    }

    // Optional lifecycle management
    attachEvents() {
        this.modalTarget.addEventListener('shown.bs.modal', this.handleShown)
        this.modalTarget.addEventListener('hidden.bs.modal', this.handleHidden)
    }

    detachEvents() {
        this.modalTarget.removeEventListener('shown.bs.modal', this.handleShown)
        this.modalTarget.removeEventListener('hidden.bs.modal', this.handleHidden)
    }

    disconnect() {
        this.detachEvents()
        this.bootstrapModal.dispose()
    }

}
