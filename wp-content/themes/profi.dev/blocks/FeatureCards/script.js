import { BaseElement } from "~js/scripts.js"

class ProfiDevFeatureCard extends BaseElement {
	connectedCallback () {
		super.connectedCallback()
		this.container = this.querySelector(".theme-text-element")
		this.button = this.querySelector(".read-more")

		if (!this.container || !this.button) {return}

		this.textMore = this.button.textContent.trim()
		this.textLess = this.button.getAttribute("data-text-less") || "Read less"

		this.checkContentHeight()
		this.button.addEventListener("click", () => this.toggleReadMore())

		this.resizeObserver = new ResizeObserver(() => {
			if (!this.container.classList.contains("is-active")) {
				this.checkContentHeight()
			}
		})
		this.resizeObserver.observe(this.container)
	}

	checkContentHeight () {
		const style = window.getComputedStyle(this.container)
		const maxHeight = parseInt(style.maxHeight)

		if (this.container.scrollHeight <= maxHeight) {
			this.container.classList.add("is-visible")
		} else {
			this.container.classList.remove("is-visible")
		}
	}

	toggleReadMore () {
		const isOpening = !this.container.classList.contains("is-active")
		const scrollHeight = this.container.scrollHeight

		const style = window.getComputedStyle(this.container)
		const collapsedHeight = style.maxHeight

		if (isOpening) {
			this.container.style.maxHeight = collapsedHeight

			this.container.offsetHeight

			this.container.classList.add("is-active")
			this.container.style.maxHeight = `${scrollHeight}px`
			this.button.textContent = this.textLess

			const onTransitionEnd = (e) => {
				if (e.propertyName === "max-height") {
					this.container.style.maxHeight = "none"
					this.container.removeEventListener("transitionend", onTransitionEnd)
				}
			}
			this.container.addEventListener("transitionend", onTransitionEnd)

		} else {
			this.container.style.maxHeight = `${this.container.offsetHeight}px`

			this.container.offsetHeight

			this.container.classList.remove("is-active")
			this.container.style.maxHeight = ""
			this.button.textContent = this.textMore

			this.scrollIntoView({ behavior: "smooth", block: "nearest" })
		}
	}

	disconnectedCallback () {
		this.resizeObserver?.disconnect()
	}
}

if (!customElements.get("profidev-feature-card")) {
	customElements.define("profidev-feature-card", ProfiDevFeatureCard)
}
