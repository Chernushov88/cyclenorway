import { BaseElement } from "~js/scripts.js"
import Swiper from "swiper"
import { Navigation, A11y, Pagination, EffectFade } from "swiper/modules"

class TestimonialsSliderElement extends BaseElement {
	connectedCallback () {
		super.connectedCallback()
		const slider = this.querySelector(".swiper")
		if (!slider) {return}

		const nextEl = this.querySelector(".theme-slider-controls .next")
		const prevEl = this.querySelector(".theme-slider-controls .prev")
		const paginationEl = this.querySelector(".slider-pagination")

		const params = {
			slidesPerView: 1,
			loop: true,
			effect: "fade",
			fadeEffect: { crossFade: true },
			modules: [Navigation, A11y, Pagination, EffectFade],
			navigation: nextEl && prevEl ? { nextEl, prevEl } : undefined,
			pagination: paginationEl ? { el: paginationEl, clickable: true } : undefined,
		}

		this.swiper = new Swiper(slider, params)
	}

	disconnectedCallback () {
		this.swiper?.destroy(true, true)
		this.swiper = null
	}
}

if (!customElements.get("profidev-testimonials-slider")) {
	customElements.define("profidev-testimonials-slider", TestimonialsSliderElement)
}