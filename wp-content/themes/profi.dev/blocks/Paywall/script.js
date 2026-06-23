import { BaseElement } from "~js/scripts.js"

class PayWallElement extends BaseElement {
	onSubmitForm = undefined

	connectedCallback () {
		super.connectedCallback()
		this.onSubmitForm = this.on(this.querySelectorAll("form.paywall-form"), "submit", this.onSubmit.bind(this))
	}

	onSubmit (e) {
		e.preventDefault()
		const membership = this.querySelector("[name=\"memberships\"]:checked")
		if (!membership) {
			return
		}
		window.location = membership.value
	}

	disconnectedCallback () {
		this.onSubmitForm?.()
	}
}

if (!customElements.get("profidev-paywall")) {
	customElements.define("profidev-paywall", PayWallElement)
}
