import { BaseElement } from "~js/scripts.js"
import Swiper from "swiper"
import { Navigation, A11y, Pagination } from "swiper/modules"

class PostSliderElement extends BaseElement {
	connectedCallback () {
		super.connectedCallback()
		const slider = this.querySelector(".swiper")
		if (!slider) {return}

		const nextEl = this.querySelector(".theme-slider-controls .next")
		const prevEl = this.querySelector(".theme-slider-controls .prev")

		const params = {
			slidesPerView: "auto",
			loop: true,
			modules: [Navigation, A11y, Pagination],
			navigation: nextEl && prevEl ? { nextEl, prevEl } : undefined,
			pagination: {
				el: this.querySelector(".slider-pagination"),
				clickable: true,
				dynamicBullets: true
			},
		}

		this.swiper = new Swiper(slider, params)
	}

	disconnectedCallback () {
		this.swiper?.destroy(true, true)
		this.swiper = null
	}
}

if (!customElements.get("profidev-post-slider")) {
	customElements.define("profidev-post-slider", PostSliderElement)
}
