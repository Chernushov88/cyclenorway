import { BaseElement } from "~js/scripts.js"

class ReviewElement extends BaseElement {
	section = null
	bg = null
	raf = null

	connectedCallback () {
		super.connectedCallback()

		this.section = this.closest(".profidev-reviews")
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

			const maxShift = this.bg.offsetHeight * 0.05
			const shift = (0.5 - progress) * maxShift * 2

			this.bg.style.transform = `translateY(${shift}px)`
		}
	}
}

if (!customElements.get("profidev-review")) {
	customElements.define("profidev-review", ReviewElement)
}