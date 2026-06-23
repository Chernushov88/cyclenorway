import { BaseElement } from "~js/scripts.js"

class PricingPlansElement extends BaseElement {
	section = null
	bg = null
	raf = null

	connectedCallback () {
		super.connectedCallback()

		this.section = this.closest(".profidev-pricing-plans")
		this.bg = this.querySelector(".bg-image")

		if (!this.section || !this.bg) {return}

		this.updateParallax = this.updateParallax.bind(this)
		this.onScroll = this.onScroll.bind(this)

		window.addEventListener("scroll", this.onScroll, { passive: true })

		this.updateParallax()
	}

	disconnectedCallback () {
		window.removeEventListener("scroll", this.onScroll)

		if (this.raf) {
			cancelAnimationFrame(this.raf)
			this.raf = null
		}
	}

	onScroll () {
		if (this.raf) {return}

		this.raf = requestAnimationFrame(() => {
			this.updateParallax()
			this.raf = null
		})
	}

	updateParallax () {
		const rect = this.section.getBoundingClientRect()
		const windowHeight = window.innerHeight

		if (rect.top < windowHeight && rect.bottom > 0) {

			const progress = (windowHeight - rect.top) / (windowHeight + rect.height)

			const maxShift = this.bg.offsetWidth * 0.05
			const shift = (0.5 - progress) * maxShift * 2

			this.bg.style.transform = `translateX(${shift}px)`
		}
	}
}

if (!customElements.get("profidev-pricing-plans")) {
	customElements.define("profidev-pricing-plans", PricingPlansElement)
}